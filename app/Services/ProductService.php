<?php

namespace App\Services;

use App\Services\CommonService;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Models\Setting;
use App\Models\SettingPolicy;
use App\Models\Item;
use Goutte\Client;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Models\SettingShipping;
use App\Models\ShippingFee;
use App\Models\CategoryFee;
use App\Models\MtbStore;
use App\Models\MtbExchangeRate;
use App\Models\ItemSpecific;
use App\Models\ItemImage;
use DB;
use Log;

class ProductService extends CommonService
{
    protected $setting;
    protected $settingPolicy;
    protected $product;
    protected $settingShipping;
    protected $shippingFee;
    protected $categoryFee;
    protected $mtbStore;
    protected $exchangeRate;
    protected $itemSpecific;
    protected $itemImage;

    public function __construct(
        Setting $setting,
        SettingPolicy $settingPolicy,
        Item $product,
        ShippingFee $shippingFee,
        SettingShipping $settingShipping,
        CategoryFee $categoryFee,
        MtbStore $mtbStore,
        MtbExchangeRate $exchangeRate,
        ItemSpecific $itemSpecific,
        ItemImage $itemImage
    ) {
        $this->setting         = $setting;
        $this->settingPolicy   = $settingPolicy;
        $this->product         = $product;
        $this->settingShipping = $settingShipping;
        $this->shippingFee     = $shippingFee;
        $this->categoryFee     = $categoryFee;
        $this->mtbStore        = $mtbStore;
        $this->exchangeRate    = $exchangeRate;
        $this->itemSpecific    = $itemSpecific;
        $this->itemImage       = $itemImage;
    }

    /**
     * api get item ebay info
     * @param  integer $itemId
     * @return Illuminate\Http\Response
     */
    public function apiGetItemEbayInfo($itemId)
    {
        $url                = config('api_info.api_ebay_get_item') . $itemId;
        $result             = $this->callApi(null, null, $url, 'get');
        $response['status'] = false;
        if ($result['Ack'] == 'Failure') {
            return response()->json($response);
        }
        $userId             = Auth::user()->id;
        $settingData        = $this->setting->getSettingOfUser($userId);
        $settingPolicyData  = $this->settingPolicy->getSettingPolicyOfUser($userId);
        $data               = $this->formatDataEbayInfo($result, $settingData, $settingPolicyData);
        $response['status'] = true;
        $response['data']   = view('admin.product.component.item_ebay_info', compact('data'))->render();
        return response()->json($response);
    }

    /**
     * format data ebay info
     * @param  array $data
     * @param  array $settingItem
     * @param  array $settingPolicyData
     * @return array
     */
    public function formatDataEbayInfo($data, $settingItem, $settingPolicyData)
    {
        // data dtb_item
        $result['dtb_item'] = [
            'item_name'      => $data['Item']['Title'],
            'category_id'    => $data['Item']['PrimaryCategoryID'],
            'category_name'  => $data['Item']['PrimaryCategoryName'],
            'condition_id'   => $data['Item']['ConditionID'],
            'condition_name' => $data['Item']['ConditionDisplayName'],
            'price'          => $data['Item']['ConvertedCurrentPrice'],
            'duration'       => Item::VALUE_DURATION_30_DAY,
            'quantity'       => $settingItem->quantity,
        ];

        //data dtb_item_specifics
        $result['dtb_item_specifics'] = [];
        foreach ($data['Item']['ItemSpecifics']['NameValueList'] as $specific) {
            $item['name']  = $specific['Name'];
            $item['value'] = $specific['Value'];
            $result['dtb_item_specifics'][] = $item;
        }

        //data dtb_setting_policies
        $shippingType = [];
        $paymentType  = [];
        $returnType   = [];
        foreach ($settingPolicyData as $key => $policy) {
            if ($policy->policy_type == SettingPolicy::TYPE_SHIPPING) {
                $shippingType[$policy->id] = $policy->policy_name;
            } elseif ($policy->policy_type == SettingPolicy::TYPE_PAYMENT) {
                $paymentType[$policy->id] = $policy->policy_name;
            } else {
                $returnType[$policy->id] = $policy->policy_name;
            }
        }
        $result['dtb_setting_policies'] = [
            'shipping' => $shippingType,
            'payment'  => $paymentType,
            'return'   => $returnType
        ];
        $result['duration']['option'] = $this->product->getDurationOption();

        return $result;
    }

    /**
     * api get item yahoo or amazon info
     * @param  integer $itemId
     * @param  integer $type
     * @return Illuminate\Http\Response
     */
    public function apiGetItemYahooOrAmazonInfo($itemId, $type)
    {
        $response['status'] = false;
        // if ($type == $this->product->getOriginTypeYahooAuction()) {
        //     $isTypeAmazon = true;
            $url     = config('api_info.api_yahoo_action_info') . $itemId;
            $client  = new Client();
            $crawler = $client->request('GET', $url);
            $crawler = $crawler->filterXPath('//*[@id="l-sub"]/div[1]/ul/li[2]/div/dl/dd')->first();
            $price   = null;
            if ($crawler->count()) {
                $price = $crawler->text();
            }

            $crawler = $client->request('GET', $url);
            $arrayImage = [];
            $index = 0;
            $crawler->filterXPath('//*[@id="l-main"]/div/div[1]/div[1]/ul/li/div/img')->each(function ($node) use (&$arrayImage, $index) {
                $index++;
                $url = $node->attr('src');
                $arrayItem = explode(".", $url);
                $type = array_pop($arrayItem);
                $item = [
                    'name' => '',
                    'type' => 'image/' . $type,
                    'extension' => $type,
                    'file' => $url,
                ];
                $arrayImage[] = $item;
            });
            $arrayImageFormApi = [];
            Storage::makeDirectory('/upload/item-images');
            foreach ($arrayImage as $key => &$item) {
                if (!Storage::disk(env('FILESYSTEM_DRIVER'))->exists('upload/item-images/' . $itemId . '_' . $key . '.' . $item['extension'])) {
                    $client  = new Client();
                    $client->getClient()->get($item['file'], [
                        'save_to' => storage_path('app/public/upload/item-images/' . $itemId . '_' . $key . '.' . $item['extension']),
                        'headers'=> [ 'Referer' => $item['file']]
                    ]);
                }
                $item['file'] = asset('storage/upload/item-images/' . $itemId . '_' . $key . '.' . $item['extension']);
                array_push($arrayImageFormApi, $itemId . '_' . $key . '.' . $item['extension']);
            }

            Session::forget('image-from-api');
            Session::push('image-from-api', $arrayImageFormApi);
        // } else {
        //     $isTypeAmazon = true;
        //     // call api amazon
        // }
        $isTypeAmazon = true;
        if (!count($arrayImage)) {
            return response()->json($response);
        }
        if ($isTypeAmazon) {
            $data['dtb_item']['product_size'] = 'M';
            $data['dtb_item']['commodity_weight'] = 950;
            $data['dtb_item']['length'] = 11;
            $data['dtb_item']['height'] = 11;
            $data['dtb_item']['width'] = 11;
        }
        $data['dtb_item']['buy_price'] = $price;
        $response['is_type_amazon'] = $isTypeAmazon;
        $response['status'] = true;
        $response['image'] = $arrayImage;
        $response['data'] = view('admin.product.component.item_yahoo_or_amazon_info', compact('data', 'arrayImage'))->render();
        return response()->json($response);

    }

    /**
     * calculator profit
     * @param  array $input
     * @return Illuminate\Http\Response
     */
    public function calculatorProfit($input)
    {
        $data['istTypeAmazon'] = $input['type'] == $this->product->getOriginTypeAmazon() ? true : false;
        if ($data['istTypeAmazon']) {
            $this->calculatorProfitTypeAmazon($data, $input);
        }
        $response['status'] = true;
        $response['data']   = view('admin.product.component.calculator_info', compact('data'))->render();
        return response()->json($response);
    }

    /**
     * get setting shipping of user
     * @param  array $input
     * @return array
     */
    public function getSettingShippingOfUser($input)
    {
        $length                = $input['length'];
        $height                = $input['height'];
        $width                 = $input['width'];
        $sumOfromAmazon        = $length + $height + $width;
        $userId                = Auth::user()->id;
        $settingShipping       = $this->settingShipping->getSettingShippingOfUser($userId);
        $settingShippingOption = [];
        foreach ($settingShipping as $key => $item) {
            $sideMaxSize = $item->side_max_size;
            if ($sumOfromAmazon <= $item->max_size &&
                $height < $sideMaxSize &&
                $length <= $sideMaxSize &&
                $width <= $sideMaxSize
            ) {
                $settingShippingOption[$item->id] = $item->shipping_name;
            }
        }
        return $settingShippingOption;
    }

    /**
     * format store info
     * @param  array $stores
     * @return array
     */
    public function formatStoreInfo($stores)
    {
        $arrayCategoryFee = ['standard_fee_rate', 'basic_fee_rate', 'premium_fee_rate', 'anchor_fee_rate'];
        $result = [];
        foreach ($stores as $key => $store) {
            $result[$store->id] = $arrayCategoryFee[$key];
        }
        return $result;
    }

    /**
     * calculator profit type amazon
     * @param  array &$data
     * @param  array $input
     * @return none
     */
    public function calculatorProfitTypeAmazon(&$data, $input)
    {
        $data['dtb_item']['product_size']     = $input['product_size'];
        $data['dtb_item']['commodity_weight'] = $input['commodity_weight'];
        $settingShippingOption                = $this->getSettingShippingOfUser($input);
        $data['setting_shipping_option']      = $settingShippingOption;
        $shippingId                           = array_keys($settingShippingOption);
        $shippingFee                          = $this->shippingFee->getShippingFeeByShippingId($shippingId[0], $input['commodity_weight']);
        $data['dtb_item']['ship_fee']         = $shippingFee->ship_fee;
        $userId                               = Auth::user()->id;
        $settingInfo                          = $this->setting->getSettingOfUser($userId);
        $storeIdOfUser                        = $settingInfo->store_id;
        $stores                               = $this->mtbStore->getAllStore();
        $storeInfo                            = $this->formatStoreInfo($stores);
        $typeFee                              = $storeInfo[$storeIdOfUser];
        $data['dtb_item']['ebay_fee']         = $this->categoryFee->getCategoryFeeByCategoryId($input['category_id'])->$typeFee;
        $data['dtb_item']['paypal_fee']       = $settingInfo->paypal_fee_rate  * $input['sell_price'] / 100;
        $data['dtb_item']['buy_price']        = $input['buy_price'];
        $exchangeRate                         = $this->exchangeRate->getExchangeRateLatest();
        $data['dtb_item']['profit']           = round(((float)$input['sell_price'] - $data['dtb_item']['ebay_fee'] - $data['dtb_item']['paypal_fee']- $data['dtb_item']['ship_fee']) * ($exchangeRate->rate - $settingInfo->ex_rate_diff) - (float) str_replace(',', '.', explode("円", $data['dtb_item']['buy_price'])[0]) * $settingInfo->gift_discount, 2);
    }

    /**
     * update profit
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function updateProfit($data)
    {
        $totalWeigh         = $data['commodity_weight'] + $data['material_quantity'];
        $shippingFee        = $this->shippingFee->getShippingFeeByShippingId((int) $data['setting_shipping'], $totalWeigh);
        if (!$shippingFee) {
            $result['status'] = false;
            $result['message_error']['material_quantity'] = 'Too large';
            return response()->json($result);
        }
        $result['ship_fee'] = $shippingFee->ship_fee;
        $exchangeRate       = $this->exchangeRate->getExchangeRateLatest();
        $userId             = Auth::user()->id;
        $settingInfo        = $this->setting->getSettingOfUser($userId);
        $result['profit']   = round(((float)$data['sell_price'] - $data['ebay_fee'] - $data['paypal_fee']- $result['ship_fee']) * ($exchangeRate->rate - $settingInfo->ex_rate_diff) - (float) str_replace(',', '.', explode("円", $data['buy_price'])[0]) * $settingInfo->gift_discount, 2);
        $result['status']   = true;
        return response()->json($result);
    }

    // public function postProduct($data)
    // {
    //     $dateNow = date('Y-m-d H:i:s');
    //     $data['item']['created_at'] = $dateNow;
    //     $data['item']['updated_at'] = $dateNow;
    //     $productId = $this->product->insertGetId($data['item']);
    //     $dataItemSpecifics = $this->formatDataItemSpecifics($data['dtb_item_specifics'], $productId);
    //     $this->itemSpecific->insert($dataItemSpecifics);
    //     $this->insertItemImage($data);
    // }

    /**
     * format data insert product confirm
     * @param  array $data
     * @return array
     */
    public function formatDataInsertProductConfirm($data, $dataSession)
    {
        unset($data['_token']);
        unset($data['fileuploader-list-files']);
        unset($data['files']);
        $data['istTypeAmazon'] = $data['dtb_item']['type'] == $this->product->getOriginTypeAmazon() ? true : false;
        $dataImageOld = [];
        if ($dataSession) {
            for ($i = 0; $i < $dataSession['number_file']; $i++) {
                array_push($dataImageOld, $dataSession['file_name_' . $i]);
            }
        }

        if (Session::has('image-from-api')) {
            $imageFromApi = Session::get('image-from-api')[0];
            Session::forget('image-from-api');
            foreach ($imageFromApi as $key => $item) {
                if (!in_array($item, $dataImageOld)) {
                   array_push($dataImageOld, $item);     
                }
            }
        }
        $dataImageNew = [];
        for ($i = 0; $i < $data['number_file']; $i++) {
            $file = $data['files_upload_' . $i];
            if (is_string($file)) {
                $data['url_preview_' . $i] = $file;
                $fileString = explode("/", $file);
                $data['file_name_' . $i] = array_pop($fileString);
                array_push($dataImageNew, $data['file_name_' . $i]);
            } else {
                $data['url_preview_' . $i] = $this->getBase64Image($file);
                $data['file_name_' . $i] = $this->uploadFile($file, 'upload/item-images');
            }
            unset($data['files_upload_' . $i]);
        }

        $imageDelete = array_diff($dataImageOld, $dataImageNew);
        foreach ($imageDelete as $key => $item) {
            Storage::delete('upload/item-images/' . $item);
        }
        return $data;
    }

    public function getBase64Image($image)
    {
        $path = $image->getPathname();
        $type = explode("/", $image->getClientMimeType())[1];
        $file = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($file);
    }

    /**
     * upload file
     * @param  object  $file
     * @param  string  $path
     * @param  string $rename
     * @param  array   $allowType
     * @param  integer  $maxSize
     * @param  array   $config
     * @return string
     */
    public static function uploadFile(
        $file, 
        $path, 
        $rename = true,
        $allowType = [], 
        $maxSize = null, 
        array $config = []
    ) {
        if ($file->isValid()) {
            if ($allowType) {
                $extension = $file->getClientMimeType();
                if (! in_array($extension, $allowType)) {
                    throw new Exception("Error Processing Request", 1);
                }
            }
            if ($maxSize) {
                $fileSize = $file->getClientSize();
                if ($fileSize / 1000 > $maxSize) {
                    throw new Exception("Error Processing Request", 1);
                }
            }
            if ($rename) {
                $extension = $file->getClientOriginalExtension();
                if (is_string($rename)) {
                    $fileName = $rename . '.' . $extension;
                } else {
                    $fileName = str_random(5) . '_' . time() . '.' . $extension;
                }
            } else {
                $fileName = $file->getClientOriginalName();
            }
            $fullPathOrg = $file->getRealPath();
            if ($config && isset($config['remove_exif']) && $config['remove_exif']) {
                self::removeExifImage($fullPathOrg);
            }
            Storage::put(
                $path . '/' . $fileName,
                file_get_contents($fullPathOrg)
            );
            return $fileName;
        }
        return null;
    }

    public function formatDataPageConfirm($data)
    {
        $userId = Auth::user()->id;
        $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
        $data['dtb_item']['duration'] = $this->product->getDurationOption()[$data['dtb_item']['duration']];
        $data['dtb_item']['shipping_policy_name'] = $this->getPoliciNameById(!empty($data['dtb_item']['shipping_policy_id']) ? $data['dtb_item']['shipping_policy_id'] : '' , $settingPolicyData);
        $data['dtb_item']['payment_policy_name'] = $this->getPoliciNameById(!empty($data['dtb_item']['payment_policy_id']) ? $data['dtb_item']['payment_policy_id'] : '', $settingPolicyData);
        $data['dtb_item']['return_policy_name'] = $this->getPoliciNameById(!empty($data['dtb_item']['return_policy_id']) ? $data['dtb_item']['return_policy_id'] : '', $settingPolicyData);
        $data['dtb_item']['setting_shipping_option'] = $this->settingShipping->findById($data['dtb_item']['setting_shipping_option'])->shipping_name;
        return $data;
    }

    public function formatDataPageProduct($data)
    {
        $data['duration']['option']      = $this->product->getDurationOption();
        $data['duration']['value']       = $data['dtb_item']['duration'];
        $settingShippingOption           = $this->getSettingShippingOfUser($data['dtb_item']);
        $data['setting_shipping_option'] = $settingShippingOption;
        $shippingType                    = [];
        $paymentType                     = [];
        $returnType                      = [];
        $userId                          = Auth::user()->id;
        $settingPolicyData               = $this->settingPolicy->getSettingPolicyOfUser($userId);
        foreach ($settingPolicyData as $key => $policy) {
            if ($policy->policy_type == SettingPolicy::TYPE_SHIPPING) {
                $shippingType[$policy->id] = $policy->policy_name;
            } elseif ($policy->policy_type == SettingPolicy::TYPE_PAYMENT) {
                $paymentType[$policy->id] = $policy->policy_name;
            } else {
                $returnType[$policy->id] = $policy->policy_name;
            }
        }
        $data['dtb_setting_policies'] = [
            'shipping' => $shippingType,
            'payment'  => $paymentType,
            'return'   => $returnType
        ];
        
        return $data;
    }

    public function getImageInit($data)
    {
        $arrayImage = [];
        for ($i = 0; $i < $data['number_file']; $i++) {
            $url = $data['file_name_' . $i];
            $arrayItem = explode(".", $url);
            $type = array_pop($arrayItem);
            $item = [
                'name' => '',
                'type' => 'image/' . $type,
                'extension' => $type,
                'file' => $data['url_preview_' . $i],
            ];
            $arrayImage[] = $item;
        }
        $result['status'] = true;
        $result['images']   = $arrayImage;
        return response()->json($result);
    }

    public function getPoliciNameById($id, $settingPolicyData)
    {
        foreach ($settingPolicyData as $key => $policy) {
            if ($policy->id == $id) {
                return $policy->policy_name;
            }
        }
        return;
    }

    public function postProductPublish()
    {
        try {
            DB::beginTransaction();
            $data = Session::get('product-info')[0];
            // insert item
            $dateNow = date('Y-m-d H:i:s');
            $dataItem = [
                'original_id' => $data['dtb_item']['original_id'],
                'item_id' => $data['dtb_item']['item_id'],
                'original_type' => $data['dtb_item']['type'],
                'item_name' => $data['dtb_item']['item_name'],
                'category_id' => $data['dtb_item']['category_id'],
                'category_name' => $data['dtb_item']['category_name'],
                'condition_id' => $data['dtb_item']['condition_id'],
                'condition_name' => $data['dtb_item']['condition_name'],
                'price' => $data['dtb_item']['price'],
                'duration' => $data['dtb_item']['duration'],
                'quantity' => $data['dtb_item']['quantity'],
                'shipping_policy_id' => $data['dtb_item']['shipping_policy_id'],
                'payment_policy_id' => $data['dtb_item']['payment_policy_id'],
                // 'return_policy_id' => $data['dtb_item']['return_policy_id'],
                'ship_fee' => $data['dtb_item']['ship_fee'],
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ];
            $itemId = $this->product->insertGetId($dataItem);

            // insert item_specifics
            $dataItemSpecifics = $this->formatDataItemSpecifics($data['dtb_item_specifics'], $itemId);
            $this->itemSpecific->insert($dataItemSpecifics);

            // insert item image
            $this->insertItemImage($data, $itemId);
            
            // post to ebay
            DB::commit();
            Session::forget('product-info');
            $response['status'] = true;
            return response()->json($response);
        } catch (Exception $ex) {
            dd($ex);
            DB::rollback();
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
        
        // array:18 [
        //   "dtb_item" => array:25 [
        //     "item_name" => "Beats by Dr. Dre - Beats Solo3 Wireless Headphones - Rose Gold"
        //     "category_id" => "112529"
        //     "category_name" => "Consumer Electronics:Portable Audio & Headphones:Headphones"
        //     "condition_id" => "1000"
        //     "condition_name" => "New"
        //     "price" => "299.99"
        //     "duration" => "Days_120"
        //     "quantity" => "1"
        //     "shipping_policy_id" => "4"
        //     "payment_policy_id" => "3"
        //     "product_size" => "M"
        //     "commodity_weight" => "950"
        //     "length" => "11"
        //     "height" => "11"
        //     "width" => "11"
        //     "material_quantity" => "11"
        //     "setting_shipping_option" => "1"
        //     "ship_fee" => "1320"
        //     "ebay_fee" => "10"
        //     "paypal_fee" => "10.79964"
        //     "buy_price" => "26,200円（税 0 円）"
        //     "profit" => "-117910.48"
        //     "original_id" => "u199058848"
        //     "item_id" => "192375777401"
        //     "type" => "2"
        //   ]
        //   "dtb_item_specifics" => array:6 [
        //     0 => array:2 [
        //       "name" => "Brand"
        //       "value" => "Beats by Dr. Dre"
        //     ]
        //     1 => array:2 [
        //       "name" => "MPN"
        //       "value" => "MNET2LL/A"
        //     ]
        //     2 => array:2 [
        //       "name" => "UPC"
        //       "value" => "190198105455"
        //     ]
        //     3 => array:2 [
        //       "name" => "Manufacturer Warranty"
        //       "value" => "Yes"
        //     ]
        //     4 => array:2 [
        //       "name" => "Warranty - Parts"
        //       "value" => "1 year"
        //     ]
        //     5 => array:2 [
        //       "name" => "Warranty - Labor"
        //       "value" => "1 year"
        //     ]
        //   ]
        //   "number_file" => "7"
        //   "istTypeAmazon" => true
        //   "url_preview_0" => "http://localhost/ebayTool/public/storage/upload/item-images/u199058848_0.jpg"
        //   "file_name_0" => "u199058848_0.jpg"
        //   "url_preview_1" => "http://localhost/ebayTool/public/storage/upload/item-images/u199058848_1.jpg"
        //   "file_name_1" => "u199058848_1.jpg"
        //   "url_preview_2" => "http://localhost/ebayTool/public/storage/upload/item-images/u199058848_2.jpg"
        //   "file_name_2" => "u199058848_2.jpg"
        //   "url_preview_3" => "http://localhost/ebayTool/public/storage/upload/item-images/u199058848_3.jpg"
        //   "file_name_3" => "u199058848_3.jpg"
        //   "url_preview_4" => "http://localhost/ebayTool/public/storage/upload/item-images/u199058848_4.jpg"
        //   "file_name_4" => "u199058848_4.jpg"
        //   "url_preview_5" => "http://localhost/ebayTool/public/storage/upload/item-images/u199058848_5.jpg"
        //   "file_name_5" => "u199058848_5.jpg"
        //   "url_preview_6" => "http://localhost/ebayTool/public/storage/upload/item-images/u199058848_6.jpg"
        //   "file_name_6" => "u199058848_6.jpg"
        // ]
    }

    public function formatDataItemSpecifics($input, $productId)
    {
        $dateNow = date('Y-m-d H:i:s');
        foreach ($input as $key => &$item) {
            $item['created_at'] = $dateNow;
            $item['updated_at'] = $dateNow;
            $item['item_id'] = $productId;
        }
        return $input;
    }

    public function insertItemImage($data, $productId)
    {
        $numberFile = $data['number_file'];
        $dateNow = date('Y-m-d H:i:s');
        for ($i = 0; $i < $numberFile; $i++) {
            $itemImageId = $this->itemImage->insertGetId([
                'item_id'    => $productId,
                'item_image' => $data['file_name_' . $i],
                'created_at' => $dateNow,
                'updated_at' => $dateNow
            ]);
            $extension = 'png';
            $itemImageString = $productId . '_' . $itemImageId . '_' . date('ymd_his') . '.' .$extension;
            $this->itemImage->updateItemImageById($itemImageId, ['item_image' => $itemImageString]);
            Storage::move('upload/item-images/' . $data['file_name_' . $i], 'upload/item-images/' . $itemImageString);
        }
    }
}
