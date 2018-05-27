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
use App\Services\SignatureAmazon;
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
    protected $keyProduct;
    protected $keyImageFromApi;
    protected $pathUpload;
    protected $fullpathUpload;
    protected $pathStorageFile;

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
        $this->keyProduct      = Item::SESSION_KEY_PRODUCT_INFO;
        $this->keyImageFromApi = ItemImage::SESSION_KEY_IMAGE_FROM_API;
        $this->pathUpload      = $this->itemImage->getPathUploadFile();
        $this->fullpathUpload  = $this->itemImage->getFullPathUploadFile();
        $this->pathStorageFile = $this->itemImage->getPathStorageFile();
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
            $item['name']                   = $specific['Name'];
            $item['value']                  = $specific['Value'];
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
    public function apiGetItemYahooOrAmazonInfo($data)
    {
        $itemId             = $data['item_id'];
        $type               = $data['type'];
        $sign               = $data['sign'];
        $itemId             = $data['item_id'];
        $time               = $data['timestamp'];
        $response['status'] = false;
        if ($type == $this->product->getOriginTypeYahooAuction()) {
            $isTypeAmazon = false;
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
                    'name'      => '',
                    'type'      => 'image/' . $type,
                    'extension' => $type,
                    'file'      => $url,
                ];
                $arrayImage[] = $item;
            });
            $arrayImageFormApi = [];
            Storage::makeDirectory($this->pathUpload);
            foreach ($arrayImage as $key => &$item) {
                if (!Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->pathUpload . $itemId . '_' . $key . '.' . $item['extension'])) {
                    $client  = new Client();
                    $client->getClient()->get($item['file'], [
                        'save_to' => storage_path($this->fullpathUpload . $itemId . '_' . $key . '.' . $item['extension']),
                        'headers'=> [ 'Referer' => $item['file']]
                    ]);
                }
                $item['file'] = asset($this->pathStorageFile . $itemId . '_' . $key . '.' . $item['extension']);
                array_push($arrayImageFormApi, $itemId . '_' . $key . '.' . $item['extension']);
            }

            Session::forget($this->keyImageFromApi);
            Session::push($this->keyImageFromApi, $arrayImageFormApi);
        } else {
            $isTypeAmazon = true;
            $url        = config('api_info.api_amazon_get_item');
            // $body       = config('api_info.body_request_api_amazon_get_item');
            // $parameters = config('api_info.parameters_api_amazon_get_item');
            // $header     = config('api_info.header_api_amazon_get_item');
            // $body['Query'] = 'B0742J781D';
            $body = config('api_info.body_request_api_amazon_get_item');
            
            $gensign = config('api_info.gen_sign');
            // $body .= 'B0742J781D';
            $signalture = $this->getSignatureAmazon($gensign);
            //dd($signalture, self::encodeNew($signalture));
            // $signalture = self::encodeNew($signalture);
            // $signalture = str_replace(
            //         array('+', '=', '/'),
            //         array('-', '_', '~'),
            //         $signalture);
            // $body .= '&Signature=' . $signalture;
            // $body .= '&Timestamp=' . date('Y-m-d\Th:m:s\Z');
            // dd($signalture);
            // $signalture = self::encodeNew($signalture);
            // dd($signalture, $sign);

            $body = [
                'AWSAccessKeyId' => 'AKIAJWROE4YTDKN5COQQ',
                'Action' => 'ListMatchingProducts',
                'SellerId' => 'A2GI94OS9KGZVF',
                'MWSAuthToken' => 'amzn.mws.f8b1b1e5-f8df-3d8c-48ff-d8655ad92d86',
                'SignatureVersion' => 2,
                'Timestamp' => date(DATE_ISO8601, time()),
                'Version' => '2011-10-01',
                'Signature' => $sign,
                'SignatureMethod' => 'HmacSHA256',
                'MarketplaceId' => 'A1VC38T7YXB528',
                'Query' => '0439708184 ',
            ];
            // dd($sign);
            // $body['Signature'] = $sign;
            // $body['Signature'] = $signalture;
            // $body['Timestamp'] = date('Y-m-d\Th:m:s\Z');
            // $body['Timestamp'] = $timestamp;
            // dd(date(DATE_ISO8601, time()));
            // $body['Timestamp'] = date(DATE_ISO8601, time());
            // $body['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
            // $body['Timestamp'] = $time;
            // dd($body);
            // $body['Timestamp'] = $data['timestamp'];
            // https://github.com/amzn/amazon-pay-sdk-php
            // $body = null;
            //dd($signalture);
            // dd($body);
            // dd($body);
            $result             = $this->callApi(null, $body, $url, 'post', true);
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
                // call api amazon
        }
        $isTypeAmazon = true;
        if (!count($arrayImage)) {
            return response()->json($response);
        }
        if ($isTypeAmazon) {
            $data['dtb_item']['product_size']     = 'M';
            $data['dtb_item']['commodity_weight'] = 950;
            $data['dtb_item']['length']           = 11;
            $data['dtb_item']['height']           = 11;
            $data['dtb_item']['width']            = 11;
        }
        $data['dtb_item']['buy_price'] = $price;
        $response['is_type_amazon']    = $isTypeAmazon;
        $response['status']            = true;
        $response['image']             = $arrayImage;
        $response['data']              = view('admin.product.component.item_yahoo_or_amazon_info', compact('data', 'arrayImage'))->render();
        return response()->json($response);

    }

    public function getSignatureAmazon($parameters)
    {
        $configs               = $parameters;
        $configs['sandbox']    = false;
        $configs['region']     = 'jp';
        $configs['secret_key'] = 'l4CCqytm56ps5QFw7AFv347bKxqzJWK4xL2hrVmb';
        // unset($configs['SecretKey']);
        // unset($parameters['SecretKey']);
        $signatureObj          = new SignatureAmazon($configs, $parameters);
        return $signatureObj->getSignature();
    }

    public function encodeNew($string)
    {
        return str_replace('%7E', '~', rawurlencode($string));
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

        if (Session::has($this->keyImageFromApi)) {
            $imageFromApi = Session::get($this->keyImageFromApi)[0];
            Session::forget($this->keyImageFromApi);
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
                $fileString                = explode("/", $file);
                $data['file_name_' . $i]   = array_pop($fileString);
                array_push($dataImageNew, $data['file_name_' . $i]);
            } else {
                $data['url_preview_' . $i] = $this->getBase64Image($file);
                $data['file_name_' . $i]   = $this->uploadFile($file, $this->pathUpload);
            }
            unset($data['files_upload_' . $i]);
        }

        $imageDelete = array_diff($dataImageOld, $dataImageNew);
        foreach ($imageDelete as $key => $item) {
            Storage::delete($this->pathUpload . $item);
        }
        return $data;
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
        $data['dtb_item']['duration']             = $this->product->getDurationOption()[$data['dtb_item']['duration']];
        $data['dtb_item']['shipping_policy_name'] = $this->getPoliciNameById(!empty($data['dtb_item']['shipping_policy_id']) ? $data['dtb_item']['shipping_policy_id'] : '' , $settingPolicyData);
        $data['dtb_item']['payment_policy_name']  = $this->getPoliciNameById(!empty($data['dtb_item']['payment_policy_id']) ? $data['dtb_item']['payment_policy_id'] : '', $settingPolicyData);
        $data['dtb_item']['return_policy_name']   = $this->getPoliciNameById(!empty($data['dtb_item']['return_policy_id']) ? $data['dtb_item']['return_policy_id'] : '', $settingPolicyData);
        if (isset($data['dtb_item']['setting_shipping_option'])) {
            $data['dtb_item']['setting_shipping_option'] = $this->settingShipping->findById($data['dtb_item']['setting_shipping_option'])->shipping_name;
        }
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
                'name'      => '',
                'type'      => 'image/' . $type,
                'extension' => $type,
                'file'      => $data['url_preview_' . $i],
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
            $data = Session::get($this->keyProduct)[0];
            // insert item
            $dateNow = date('Y-m-d H:i:s');
            $dataItem = [
                'original_id'         => $data['dtb_item']['original_id'],
                'item_id'             => $data['dtb_item']['item_id'],
                'original_type'       => $data['dtb_item']['type'],
                'item_name'           => $data['dtb_item']['item_name'],
                'category_id'         => $data['dtb_item']['category_id'],
                'category_name'       => $data['dtb_item']['category_name'],
                'condition_id'        => $data['dtb_item']['condition_id'],
                'condition_name'      => $data['dtb_item']['condition_name'],
                'price'               => $data['dtb_item']['price'],
                'duration'            => $data['dtb_item']['duration'],
                'quantity'            => $data['dtb_item']['quantity'],
                'shipping_policy_id'  => $data['dtb_item']['shipping_policy_id'],
                'payment_policy_id'   => $data['dtb_item']['payment_policy_id'],
                // 'return_policy_id' => $data['dtb_item']['return_policy_id'],
                // 'ship_fee'         => $data['dtb_item']['ship_fee'],
                'created_at'          => $dateNow,
                'updated_at'          => $dateNow,
            ];
            $itemId = $this->product->insertGetId($dataItem);

            // insert item_specifics
            $dataItemSpecifics = $this->formatDataItemSpecifics($data['dtb_item_specifics'], $itemId);
            $this->itemSpecific->insert($dataItemSpecifics);

            // insert item image
            $this->insertItemImage($data, $itemId);
            
            // post to ebay
            DB::commit();
            Session::forget($this->keyProduct);
            $response['status'] = true;
            return response()->json($response);
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    public function formatDataItemSpecifics($input, $productId)
    {
        $dateNow = date('Y-m-d H:i:s');
        foreach ($input as $key => &$item) {
            $item['created_at'] = $dateNow;
            $item['updated_at'] = $dateNow;
            $item['item_id']    = $productId;
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
            $arrayItem       = explode(".", $data['file_name_' . $i]);
            $extension       = array_pop($arrayItem);
            $itemImageString = $productId . '_' . $itemImageId . '_' . date('ymd_his') . '.' . $extension;
            $this->itemImage->updateItemImageById($itemImageId, ['item_image' => $itemImageString]);
            $this->pathUpload = $this->itemImage->getPathUploadFile();
            if (Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->pathUpload . $data['file_name_' . $i])) {
                Storage::move($this->pathUpload . $data['file_name_' . $i], $this->pathUpload . $itemImageString);
            }
        }
    }
}
