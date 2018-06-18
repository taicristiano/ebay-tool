<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;

class ProductPostService extends CommonService
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
    protected $keyImageEditFromApi;
    protected $pathUpload;
    protected $fullPathUpload;
    protected $pathStorageFile;
    protected $keyProductEdit;

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
        ItemImage $itemImage,
        EbayClient $ebayClient
    ) {
        $this->setting             = $setting;
        $this->settingPolicy       = $settingPolicy;
        $this->product             = $product;
        $this->settingShipping     = $settingShipping;
        $this->shippingFee         = $shippingFee;
        $this->categoryFee         = $categoryFee;
        $this->mtbStore            = $mtbStore;
        $this->exchangeRate        = $exchangeRate;
        $this->itemSpecific        = $itemSpecific;
        $this->itemImage           = $itemImage;
        $this->ebayClient          = $ebayClient;
        $this->keyProduct          = Item::SESSION_KEY_PRODUCT_INFO;
        $this->keyProductEdit      = Item::SESSION_KEY_PRODUCT_EDIT_INFO;
        $this->keyImageFromApi     = ItemImage::SESSION_KEY_IMAGE_FROM_API;
        $this->keyImageEditFromApi = ItemImage::SESSION_KEY_IMAGE_EDIT_FROM_API;
        $this->pathUpload          = $this->itemImage->getPathUploadFile();
        $this->fullPathUpload      = $this->itemImage->getfullPathUploadFile();
        $this->pathStorageFile     = $this->itemImage->getPathStorageFile();
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
            $response['message_error'] = Lang::get('view.item_not_found');
            return response()->json($response);
        }
        $userId             = Auth::user()->id;
        $settingData        = $this->setting->getSettingOfUser($userId);
        $conditionIdList    = $this->product->getConditionIdList();
        $data               = $this->formatDataEbayInfo($result, $settingData);
        $response['status'] = true;
        $response['data']   = view('admin.product.component.item_ebay_info', compact('data', 'conditionIdList'))->render();
        return response()->json($response);
    }

    /**
     * format data ebay info
     * @param  array $data
     * @param  array $settingItem
     * @return array
     */
    public function formatDataEbayInfo($data, $settingItem)
    {
        // data dtb_item
        $result['dtb_item'] = [
            'item_name'      => $data['Item']['Title'],
            'category_id'    => $data['Item']['PrimaryCategoryID'],
            'category_name'  => $data['Item']['PrimaryCategoryName'],
            'condition_id'   => !empty($data['Item']['ConditionID']) ? $data['Item']['ConditionID'] : null,
            'condition_name' => $data['Item']['ConditionDisplayName'],
            'price'          => $data['Item']['ConvertedCurrentPrice'],
            'duration'       => $settingItem->duration,
            'quantity'       => $settingItem->quantity,
        ];

        //data dtb_item_specifics
        $result['dtb_item_specifics'] = [];
        foreach ($data['Item']['ItemSpecifics']['NameValueList'] as $specific) {
            if ($specific['Name'] == 'UPC') {
                $result['dtb_item']['jan_upc'] = $specific['Value'];
            }
            $item['name']                   = $specific['Name'];
            $item['value']                  = $specific['Value'];
            $result['dtb_item_specifics'][] = $item;
        }

        //data dtb_setting_policies
        $result['dtb_setting_policies'] = $this->getDataSettingPolicies();
        $result['duration']['option'] = $this->product->getDurationOption();

        return $result;
    }

    /**
     * api get item yahoo or amazon info
     * @param  array $input
     * @return Illuminate\Http\Response
     */
    public function apiGetItemYahooOrAmazonInfo($input)
    {
        $itemId             = $input['item_id'];
        $type               = $input['type'];
        $price              = 0;
        $commodityWeight    = 0;
        $length             = 0;
        $height             = 0;
        $width              = 0;
        $response['status'] = false;
        if ($type == $this->product->getOriginTypeYahooAuction()) {
            $isTypeAmazon = false;
            $url          = config('api_info.api_yahoo_action_info') . $itemId;
            $client       = new Client();
            $crawler      = $client->request('GET', $url);
            $crawler      = $crawler->filterXPath(config('api_info.regex_get_price_yahoo_auction'))->first();
            if ($crawler->count()) {
                $price     = $crawler->text();
                $arrayItem = explode("円", $price);
                $price     = $arrayItem[0];
                $price     = round((float) str_replace(',', '', $price), 2);
            }

            $crawler    = $client->request('GET', $url);
            $arrayImage = [];
            $crawler->filterXPath(config('api_info.regex_get_image_yahoo_auction'))->each(function ($node) use (&$arrayImage) {
                $url       = $node->attr('src');
                $arrayItem = explode(".", $url);
                $type      = array_pop($arrayItem);
                $item      = [
                    'name'      => '',
                    'type'      => 'image/' . $type,
                    'extension' => $type,
                    'file'      => $url,
                ];
                $arrayImage[] = $item;
            });
        } else {
            $isTypeAmazon = true;
            $exchangeRate = $this->exchangeRate->getExchangeRateLatest();
            $userId       = Auth::user()->id;
            $settingInfo  = $this->setting->getSettingOfUser($userId);
            if (!$this->checkAmazonInfoOfUser($settingInfo)) {
                $response['message_error'] = Lang::get('view.you_do_not_setting_amazon_information');
                return response()->json($response);
            }
            $marketplaceId = config('api_info.market_place_id_amazon');
            $client = new AmazonMwsClient(
                $settingInfo->mws_access_key,
                $settingInfo->mws_secret_key,
                $settingInfo->seller_id,
                [$marketplaceId],
                $settingInfo->mws_auth_token,
                'MCS/MwsClient',
                '1.0',
                config('api_info.api_amazon_get_item')
            );
            $optionalParams = [
                'Query'         => $itemId,
                'MarketplaceId' => $marketplaceId
            ];
            $data = $client->send('ListMatchingProducts', '/Products/2011-10-01', $optionalParams);
            if (!count($data)) {
                $response['message_error'] = Lang::get('view.item_not_found');
                return response()->json($response);
            }
            $arrayImage  = [];
            foreach ($data as $key => $item) {
                if (!empty($item['ns2:SmallImage']['ns2:URL'])) {
                    $url       = $item['ns2:SmallImage']['ns2:URL'];
                    $arrayItem = explode(".", $url);
                    $type      = array_pop($arrayItem);
                    $itemImage = [
                        'name'      => '',
                        'type'      => 'image/' . $type,
                        'extension' => $type,
                        'file'      => $url,
                    ];
                    $arrayImage[] = $itemImage;
                }

                if (!empty($item['ns2:ListPrice']['ns2:Amount'])) {
                    $price     = $item['ns2:ListPrice']['ns2:Amount'];
                    $priceType = $item['ns2:ListPrice']['ns2:CurrencyCode'];
                    if ($price && $priceType == "USD") {
                        $price = $price * ($exchangeRate->rate - $settingInfo->ex_rate_diff);
                    }
                    $price = round((float) $price, 2);
                }
                if (!empty($item['ns2:PackageDimensions'])) {
                    $commodityWeight = !empty($item['ns2:PackageDimensions']['ns2:Weight']) ? $item['ns2:PackageDimensions']['ns2:Weight'] : 0;
                    $length          = !empty($item['ns2:PackageDimensions']['ns2:Length']) ? $item['ns2:PackageDimensions']['ns2:Length'] : 0;
                    $height          = !empty($item['ns2:PackageDimensions']['ns2:Height']) ? $item['ns2:PackageDimensions']['ns2:Height'] : 0;
                    $width           = !empty($item['ns2:PackageDimensions']['ns2:Width']) ? $item['ns2:PackageDimensions']['ns2:Width'] : 0;
                    $length          = round($length * 2.54, 2);
                    $height          = round($height * 2.54, 2);
                    $width           = round($width * 2.54, 2);
                }
            }
        }
        if (!count($arrayImage)) {
            $response['message_error'] = Lang::get('view.item_not_found');
            return response()->json($response);
        }

        $arrayImageFormApi = [];
        Storage::makeDirectory($this->pathUpload);
        foreach ($arrayImage as $key => &$item) {
            if (!Storage::disk(env('FILESYSTEM_DRIVER'))->exists($this->pathUpload . $itemId . '_' . $key . '.' . $item['extension'])) {
                $client  = new Client();
                $client->getClient()->get($item['file'], [
                    'save_to' => storage_path($this->fullPathUpload . $itemId . '_' . $key . '.' . $item['extension']),
                    'headers' => [ 'Referer' => $item['file']]
                ]);
            }
            $item['file'] = asset($this->pathStorageFile . $itemId . '_' . $key . '.' . $item['extension']);
            array_push($arrayImageFormApi, $itemId . '_' . $key . '.' . $item['extension']);
        }

        $keyImageFromApi = $this->keyImageFromApi;
        if (!empty($input['id'])) {
            $keyImageFromApi = $this->keyImageEditFromApi . '_' . $input['id'];
        }
        Session::forget($keyImageFromApi);
        Session::push($keyImageFromApi, $arrayImageFormApi);

        $dataResult['dtb_item']['commodity_weight'] = round($commodityWeight * 453.59237, 2);
        $dataResult['dtb_item']['length']           = $length;
        $dataResult['dtb_item']['height']           = $height;
        $dataResult['dtb_item']['width']            = $width;
        $dataResult['dtb_item']['buy_price']        = $price;
        $response['is_type_amazon']                 = $isTypeAmazon;
        $response['status']                         = true;
        $response['image']                          = $arrayImage;
        $response['data']                           = view('admin.product.component.item_yahoo_or_amazon_info', compact('dataResult'))->render();
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
        // if ($data['istTypeAmazon']) {
            $this->calculatorProfitTypeAmazon($data, $input);
        // } else {
            // $this->calculatorProfitTypeYahoo($data, $input);
        // }
        $response['status'] = true;
        $response['data']   = view('admin.product.component.calculator_info', compact('data'))->render();
        return response()->json($response);
    }

    /**
     * calculator profit type amazon
     * @param  array $data
     * @param  array $input
     * @return void
     */
    public function calculatorProfitTypeAmazon(&$data, $input)
    {
        $exchangeRate = $this->exchangeRate->getExchangeRateLatest();
        $userId       = Auth::user()->id;
        $settingInfo  = $this->setting->getSettingOfUser($userId);
        $data['dtb_item']['height']           = $input['height'];
        $data['dtb_item']['width']            = $input['width'];
        $data['dtb_item']['length']           = $input['length'];
        $data['dtb_item']['commodity_weight'] = $input['commodity_weight'];
        $data['dtb_item']['material_quantity'] = $input['material_quantity'];
        $settingShippingOption                = $this->getSettingShippingOfUser($input);
        $data['setting_shipping_option']      = $settingShippingOption;
        $optionSelected                       = $input['setting_shipping'];
        $shippingId                           = array_keys($settingShippingOption);
        if (!isset($settingShippingOption[$optionSelected])) {
            $optionSelected = $shippingId[0];
        }
        $data['setting_shipping_selected'] = $optionSelected;
        if (!$input['ship_fee']) {
            $shippingFee                  = $this->shippingFee->getShippingFeeByShippingId($shippingId[0], !empty($input['commodity_weight']) ? $input['commodity_weight'] : 0);
            $data['dtb_item']['ship_fee'] = $shippingFee->ship_fee;
        } else {
            $data['dtb_item']['ship_fee'] = $input['ship_fee'];
        }
        $storeIdOfUser                  = $settingInfo->store_id;
        $stores                         = $this->mtbStore->getAllStore();
        $storeInfo                      = $this->formatStoreInfo($stores);
        $typeFee                        = $storeInfo[$storeIdOfUser];
        $sellPriceYen                   = round($input['sell_price'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['ebay_fee']   = round($input['sell_price'] * $this->categoryFee->getCategoryFeeByCategoryId($input['category_id'])->$typeFee / 100, 2);
        $ebayFeeYen                     = round($data['dtb_item']['ebay_fee'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['paypal_fee'] = round($settingInfo->paypal_fee_rate  * $sellPriceYen / 100 + $settingInfo->paypal_fixed_fee, 2);
        $data['dtb_item']['buy_price']  = $input['buy_price'];
        if ($data['istTypeAmazon']) {
            $data['dtb_item']['profit']     = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - $data['dtb_item']['ship_fee'] - (float)$data['dtb_item']['buy_price'] * $settingInfo->gift_discount / 100, 2);
        } else {
            $data['dtb_item']['profit']     = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - $data['dtb_item']['ship_fee'] - (float)$data['dtb_item']['buy_price'], 2);
        }
    }

    /**
     * calculator profit type yahoo
     * @param  array &$data
     * @param  array $input
     * @return void
     */
    public function calculatorProfitTypeYahoo(&$data, $input)
    {
        $userId                         = Auth::user()->id;
        $exchangeRate                   = $this->exchangeRate->getExchangeRateLatest();
        $settingInfo                    = $this->setting->getSettingOfUser($userId);
        $storeIdOfUser                  = $settingInfo->store_id;
        $stores                         = $this->mtbStore->getAllStore();
        $storeInfo                      = $this->formatStoreInfo($stores);
        $typeFee                        = $storeInfo[$storeIdOfUser];
        $sellPriceYen                   = round($input['sell_price'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['ebay_fee']   = round($input['sell_price'] * $this->categoryFee->getCategoryFeeByCategoryId($input['category_id'])->$typeFee / 100, 2);
        $ebayFeeYen                     = round($data['dtb_item']['ebay_fee'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['paypal_fee'] = round($settingInfo->paypal_fee_rate  * $sellPriceYen / 100, 2);
        $data['dtb_item']['buy_price']  = $input['buy_price'];
        $data['dtb_item']['profit']     = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - (float)$data['dtb_item']['buy_price'], 2);
    }

    /**
     * format data insert product confirm
     * @param  array $data
     * @param  array $dataSession
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
        $keyImageFromApi = $this->keyImageFromApi;
        if (!empty($data['dtb_item']['id'])) {
            $keyImageFromApi = $this->keyImageEditFromApi . '_' . $data['dtb_item']['id'];
        }
        if (Session::has($keyImageFromApi)) {
            $imageFromApi = Session::get($keyImageFromApi)[0];
            Session::forget($keyImageFromApi);
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
                $data['file_name_' . $i]   = $this->uploadFile($file, $this->pathUpload);
                $data['url_preview_' . $i] = asset($this->pathStorageFile . $data['file_name_' . $i]);
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
     * get base 64 image
     * @param  image $image
     * @return string
     */
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
     * @param  boolean $rename
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

    /**
     * format data page confirm
     * @param  array $data
     * @return array
     */
    public function formatDataPageConfirm($data)
    {
        $userId = Auth::user()->id;
        $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
        $data['dtb_item']['duration']             = $this->product->getDurationOption()[$data['dtb_item']['duration']];
        $data['dtb_item']['shipping_policy_name'] = $this->getPolicyNameById(!empty($data['dtb_item']['shipping_policy_id']) ? $data['dtb_item']['shipping_policy_id'] : '', $settingPolicyData);
        $data['dtb_item']['payment_policy_name']  = $this->getPolicyNameById(!empty($data['dtb_item']['payment_policy_id']) ? $data['dtb_item']['payment_policy_id'] : '', $settingPolicyData);
        $data['dtb_item']['return_policy_name']   = $this->getPolicyNameById(!empty($data['dtb_item']['return_policy_id']) ? $data['dtb_item']['return_policy_id'] : '', $settingPolicyData);
        if (isset($data['dtb_item']['setting_shipping_option'])) {
            $data['dtb_item']['setting_shipping_option'] = $this->settingShipping->findById($data['dtb_item']['setting_shipping_option'])->shipping_name;
        }
        $data['dtb_item']['condition_name'] = $this->product->getConditionNameById($data['dtb_item']['condition_id']);
        return $data;
    }

    /**
     * get image init
     * @param  array $data
     * @return Illuminate\Http\Response
     */
    public function getImageInit($data)
    {
        $result['status'] = true;
        $arrayImage = [];
        if (!$data) {
            $result['images'] = $arrayImage;
            return response()->json($result);
        }
        for ($i = 0; $i < $data['number_file']; $i++) {
            $url       = $data['file_name_' . $i];
            $arrayItem = explode(".", $url);
            $type      = array_pop($arrayItem);
            $item      = [
                'name'      => '',
                'type'      => 'image/' . $type,
                'extension' => $type,
                'file'      => $data['url_preview_' . $i],
            ];
            $arrayImage[] = $item;
        }
        $result['images']   = $arrayImage;
        return response()->json($result);
    }

    /**
     * post product publish
     * @param array $input
     * @return Illuminate\Http\Response
     */
    public function postProductPublish($input)
    {
        try {
            DB::beginTransaction();
            $keyProduct = $this->keyProduct;
            if (!empty($input['id'])) {
                $keyProduct = $this->keyProductEdit . '_' . $input['id'];
            }
            $data     = Session::get($keyProduct)[0];
            $dateNow  = date('Y-m-d H:i:s');
            $dataItem = [
                'original_id'           => $data['dtb_item']['original_id'],
                'original_type'         => $data['dtb_item']['type'],
                'item_name'             => $data['dtb_item']['item_name'],
                'category_id'           => $data['dtb_item']['category_id'],
                'category_name'         => $data['dtb_item']['category_name'],
                'condition_des'         => $data['dtb_item']['condition_des'],
                'jan_upc'               => $data['dtb_item']['jan_upc'],
                'condition_id'          => $data['dtb_item']['condition_id'],
                'condition_name'        => $data['dtb_item']['condition_name'],
                'price'                 => $data['dtb_item']['price'],
                'duration'              => $data['dtb_item']['duration'],
                'quantity'              => $data['dtb_item']['quantity'],
                'shipping_policy_id'    => !empty($data['dtb_item']['shipping_policy_id']) ? $data['dtb_item']['shipping_policy_id'] : null,
                'payment_policy_id'     => !empty($data['dtb_item']['payment_policy_id']) ? $data['dtb_item']['payment_policy_id'] : null,
                'return_policy_id'      => !empty($data['dtb_item']['return_policy_id']) ? $data['dtb_item']['return_policy_id'] : null,
                'item_height'           => !empty($data['dtb_item']['height']) ? $data['dtb_item']['height'] : null,
                'item_width'            => !empty($data['dtb_item']['width']) ? $data['dtb_item']['width'] : null,
                'item_length'           => !empty($data['dtb_item']['length']) ? $data['dtb_item']['length'] : null,
                'item_weight'           => !empty($data['dtb_item']['commodity_weight']) ? $data['dtb_item']['commodity_weight'] : null,
                'pack_material_weight'  => !empty($data['dtb_item']['material_quantity']) ? $data['dtb_item']['material_quantity'] : null,
                'buy_price'             => $data['dtb_item']['buy_price'],
                'ship_fee'              => isset($data['dtb_item']['ship_fee']) ? $data['dtb_item']['ship_fee'] : null,
                'last_mornitoring_date' => $dateNow,
                'temp_shipping_method'  => $data['dtb_item']['temp_shipping_method'],
                'temp_profit'           => $data['dtb_item']['profit'],
            ];
            if (!empty($input['id'])) {
                $itemId = $input['id'];
                // call api update
                
                // update item
                $this->product->updateItem($itemId, $dataItem);

                // delete dtb_item_specifics
                $this->itemSpecific->deleteByItemId($itemId);

                // update item image
                $this->updateItemImage($itemId, $data);            
            } else {
                // call addFixedPriceItem and get ebayItemId
                // $ebayItemId = $this->ebayClient->addFixedPriceItem($data);
                // insert item
                $dataItem['user_id']    = Auth::user()->id;
                $dataItem['created_at'] = $dateNow;
                $dataItem['updated_at'] = $dateNow;
                // $dataItem['item_id']    = $ebayItemId;
                $dataItem['item_id']    = 232325454;
                $itemId = $this->product->insertGetId($dataItem);
                // insert item image
                $this->insertItemImage($data, $itemId);
            }
            // insert item_specifics
            $dataItemSpecifics = $this->formatDataItemSpecifics($data['dtb_item_specifics'], $itemId);
            $this->itemSpecific->insert($dataItemSpecifics);
            DB::commit();
            Session::forget($keyProduct);
            $response['status'] = true;
            return response()->json($response);
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * format data item specifics
     * @param  array $input
     * @param  integer $productId
     * @return array
     */
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

    /**
     * insert item image
     * @param  array $data
     * @param  integer $productId
     * @return void
     */
    public function insertItemImage($data, $productId)
    {
        $numberFile = $data['number_file'];
        $dateNow    = date('Y-m-d H:i:s');
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


    /**
     * check has setting policy data
     * @return boolean
     */
    public function checkHasSettingPolicyData()
    {
        $userId = Auth::user()->id;
        return $this->settingPolicy->getSettingPolicyOfUser($userId) ? true : false;
    }

    /**
     * format message error
     * @param  array $messageError
     * @return array
     */
    public function formatMessageError($messageError)
    {
        $arrayError = [];
        foreach ($messageError as $key => $value) {
            $arrayError[str_replace('.', '_', $key)] = $value[0];
        }
        return $arrayError;
    }

    /**
     * get data for show page product post
     * @param  array $data
     * @return array
     */
    public function getDataForShowPagePostProduct($data)
    {
        if (!$data) {
            $data['istTypeAmazon'] = false;
        }
        $data['duration']['option']      = $this->product->getDurationOption();
        $data['dtb_setting_policies']    = $this->getDataSettingPolicies();
        if (empty($data['dtb_item'])) {
            $settingShippingOption             = $this->getSettingShippingOfUser(null);
            $userId                            = Auth::user()->id;
            $settingData                       = $this->setting->getSettingOfUser($userId);
            $data['dtb_item']['duration']      = $settingData->duration;
            $data['dtb_item']['quantity']      = $settingData->quantity;
            $category                          = $this->categoryFee->getFirstItem();
            $data['dtb_item']['category_id']   = $category->category_id;
            $data['dtb_item']['category_name'] = $category->category_path;
        } else {
            $settingShippingOption             = $this->getSettingShippingOfUser($data['dtb_item']);
        }
        $data['setting_shipping_option'] = $settingShippingOption;
        return $data;
    }

    /**
     * check amazon info of user
     * @param array $settingData
     * @return boolean
     */
    public function checkAmazonInfoOfUser($settingData)
    {
        if ($settingData->seller_id
            && $settingData->mws_auth_token
            && $settingData->mws_access_key
            && $settingData->mws_secret_key
            && $settingData->paypal_email
        ) {
            return true;
        }
        return false;
    }

    /**
     * update item image
     * @param  integer $productId
     * @param  array $data
     * @return void
     */
    public function updateItemImage($productId, $data)
    {
        $imageOfItem = $this->itemImage->getImageOfProduct($productId);
        $dataImageOld = [];
        $dataImageNew = [];
        foreach ($imageOfItem as $item) {
            array_push($dataImageOld, $item['item_image']);
        }

        $numberFile = $data['number_file'];
        $dateNow    = date('Y-m-d H:i:s');
        for ($i = 0; $i < $numberFile; $i++) {
            array_push($dataImageNew, $data['file_name_' . $i]);
            if (!$this->itemImage->findByImageName($data['file_name_' . $i])) {
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
        $imageDelete = array_diff($dataImageOld, $dataImageNew);
        $this->itemImage->deleteByName($imageDelete);
        foreach ($imageDelete as $key => $item) {
            Storage::delete($this->pathUpload . $item);
        }
    }
}
