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
use Illuminate\Http\UploadedFile;

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
        ];

        //data dtb_item_specifics
        $result['dtb_item_specifics'] = [];
        foreach ($data['Item']['ItemSpecifics']['NameValueList'] as $specific) {
            $item['name']  = $specific['Name'];
            $item['value'] = $specific['Value'];
            $result['dtb_item_specifics'][] = $item;
        }

        //data dtb_setting
        $settingData['duration'] = $settingItem->duration;
        $settingData['quantity'] = $settingItem->quantity;
        $result['dtb_setting']   = $settingData;

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
        $result['duration']['value']  = Item::VALUE_DURATION_30_DAY;

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
                // $client  = new Client();
                // $client->getClient()->get($url, ['save_to' => $index . '.' . $type,
                //     'headers'=>[ 'Referer' => $url]
                // ]);
                $arrayImage[] = $item;
            });
            // Storage::makeDirectory(storage_path('app/public/upload/item-images', 0755, true, true));
            // foreach ($arrayImage as $key => $item) {
            //     $client  = new Client();
            //     $client->getClient()->get($item['file'], [
            //         'save_to' => storage_path('app/public/upload/item-images/' . $itemId . '_' . $key . '.' . $item['extension']),
            //         'headers'=> [ 'Referer' => $item['file']]
            //     ]);
            // }
            // dd($arrayImage);
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

    public function postProduct($data)
    {
        $dateNow = date('Y-m-d H:i:s');
        $data['item']['created_at'] = $dateNow;
        $data['item']['updated_at'] = $dateNow;
        $productId = $this->product->insertGetId($data['item']);
        $dataItemSpecifics = $this->formatDataItemSpecifics($data['dtb_item_specifics'], $productId);
        $this->itemSpecific->insert($dataItemSpecifics);
        $this->insertItemImage($data);
    }
    
    public function formatDataItemSpecifics(&$input, $productId)
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
        $numberFile = $input['number_file'];
        $dateNow = date('Y-m-d H:i:s');
        for ($i = 0; $i < $numberFile; $i++) {
            if (!is_string($data['files_upload_' . $i])) {
                $this->uploadFile($data['files_upload_' . $i], 'public/upload/item-images');
            }
            $itemImageId = $this->itemImage->insertGetId([
                'item_id' => $productId,
                'url_image' => is_string($data['files_upload_' . $i]) ? $data['files_upload_' . $i] : '',
                'created_at' => $dateNow,
                'updated_at' => $dateNow
            ]);
            $itemImageString = $productId . '_' . $itemImageId . '_' . date('ymd_his');
            $this->itemImage->updateItemImageById($itemImageId, ['item_image' => $itemImageString]);
        }
    }

    /**
     * format data insert product confirm
     * @param  array $data
     * @return array
     */
    public function formatDataInsertProductConfirm($data)
    {
        unset($data['_token']);
        unset($data['fileuploader-list-files']);
        unset($data['files']);
        for ($i = 0; $i < $data['number_file']; $i++) {
            $file = $data['files_upload_' . $i];
            if (is_string($file)) {
                $data['url_preview_' . $i] = $file;
                $data['file_name_' . $i] = $file;
            } else {
                $data['url_preview_' . $i] = $this->getBase64Image($file);
                $data['file_name_' . $i] = $this->uploadFile($file, 'public/upload/item-images');
                // $data['file_' . $i] = [
                //     'test' => false,
                //     'originalName' => $file->getClientOriginalName(),
                //     'mimeType' => $file->getClientMimeType(),
                //     'size' => $file->getClientSize(),
                //     'path' => $file->getPathname(),
                // ];
                // $data['file_name_' . $i] = $data['files_upload_' . $i];
            }
            unset($data['files_upload_' . $i]);
        }
        // if (!is_string($data['files_upload_7'])) {
        //     $file = $data['files_upload_7'];
        //     $data['file_7'] = [
        //         'test' => false,
        //         'originalName' => $file->getClientOriginalName(),
        //         'mimeType' => $file->getClientMimeType(),
        //         'size' => $file->getClientSize(),
        //         'path' => $file->getPathname(),
        //     ];
        //     // $data['file_new'] = new UploadedFile(
        //     //     $data['file_7']['path'],
        //     //     $data['file_7']['originalName'],
        //     //     $data['file_7']['mimeType'],
        //     //     $data['file_7']['size']
        //     // );
        //     unset($data['files_upload_7']);
        //     // $data['files_upload_7'] = (array) $data['files_upload_7'];
        // }
        return $data;
    }

    public function getBase64Image($image)
    {
        $path = $image->getPathname();
        $type = explode("/", $image->getClientMimeType())[1];
        $file = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($file);
    }

    public function uploadTesst($input)
    {
        $file = new UploadedFile(
            $input['path'],
            $input['originalName'],
            $input['mimeType'],
            $input['size']
        );
        $this->uploadFile($file, 'public/upload/item-images');
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
        return $data;
    }

    public function formatDataPageProduct($data)
    {
        $data['duration']['option']      = $this->product->getDurationOption();
        $data['duration']['value']       = $data['dtb_item']['duration'];
        $data['istTypeAmazon']           = $data['dtb_item']['type'] == $this->product->getOriginTypeAmazon() ? true : false;
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
        $data['image'] = json_encode($arrayImage);
        // dd($data['image']);
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
}
