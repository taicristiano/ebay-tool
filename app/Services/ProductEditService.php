<?php

namespace App\Services;

use Illuminate\Support\Facades\Lang;
use App\Models\Item;
use App\Models\SettingPolicy;
use App\Models\ItemSpecific;
use App\Models\SettingShipping;
use App\Models\ItemImage;
use App\Models\MtbExchangeRate;
use App\Models\MtbStore;
use App\Models\Setting;
use App\Models\CategoryFee;
use App\Services\CommonService;
use Illuminate\Support\Facades\Auth;

class ProductEditService extends CommonService
{
    protected $product;
    protected $settingPolicy;
    protected $itemSpecific;
    protected $settingShipping;
    protected $itemImage;
    protected $pathStorageFile;
    protected $exchangeRate;
    protected $setting;
    protected $mtbStore;
    protected $categoryFee;

    public function __construct(
        Item $product,
        SettingPolicy $settingPolicy,
        ItemSpecific $itemSpecific,
        SettingShipping $settingShipping,
        ItemImage $itemImage,
        Setting $setting,
        MtbStore $mtbStore,
        CategoryFee $categoryFee,
        MtbExchangeRate $exchangeRate
    ) {
        $this->product         = $product;
        $this->settingPolicy   = $settingPolicy;
        $this->itemSpecific    = $itemSpecific;
        $this->settingShipping = $settingShipping;
        $this->itemImage       = $itemImage;
        $this->pathStorageFile = $this->itemImage->getPathStorageFile();
        $this->exchangeRate    = $exchangeRate;
        $this->setting         = $setting;
        $this->mtbStore        = $mtbStore;
        $this->categoryFee     = $categoryFee;
    }

    /**
     * get data for show page edit product
     * @param  array $item
     * @return array
     */
    public function getDataForShowPageEditProduct($item)
    {
        $data['dtb_item']                      = $item;
        $data['item_id']                       = $item['item_id'];
        $data['dtb_item']['commodity_weight']  = $item['item_weight'];
        $data['dtb_item']['material_quantity'] = $item['pack_material_weight'];
        $data['dtb_item']['length']            = $item['item_length'];
        $data['dtb_item']['height']            = $item['item_height'];
        $data['dtb_item']['width']             = $item['item_width'];
        $itemSpecific                          = $this->itemSpecific->getByItemId($item['id']);
        $data['dtb_item_specifics']            = $this->formatDataItemSpecifics($itemSpecific);
        $data['duration']['option']            = $this->product->getDurationOption();
        $data['dtb_setting_policies']          = $this->getDataSettingPolicies();
        $data['istTypeAmazon']                 = $item['original_type'] == $this->product->getOriginTypeAmazon() ? true : false;
        if ($data['istTypeAmazon']) {
            $this->calculatorProfitTypeAmazon($data);
        } else {
            $this->calculatorProfitTypeYahoo($data);
        }
        return $data;
    }

    /**
     * calculator profit type amazon
     * @param  array &$data
     * @return void
     */
    public function calculatorProfitTypeAmazon(&$data)
    {
        $exchangeRate                      = $this->exchangeRate->getExchangeRateLatest();
        $userId                            = Auth::user()->id;
        $settingInfo                       = $this->setting->getSettingOfUser($userId);
        $settingShippingOption             = $this->getSettingShippingOfUser($data['dtb_item']);
        $data['setting_shipping_option']   = $settingShippingOption;
        $data['setting_shipping_selected'] = 1;
        $settingInfo                       = $this->setting->getSettingOfUser($userId);
        $storeIdOfUser                     = $settingInfo->store_id;
        $stores                            = $this->mtbStore->getAllStore();
        $storeInfo                         = $this->formatStoreInfo($stores);
        $typeFee                           = $storeInfo[$storeIdOfUser];
        $sellPriceYen                      = round($data['dtb_item']['price'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['ebay_fee']      = round($data['dtb_item']['price'] * $this->categoryFee->getCategoryFeeByCategoryId($data['dtb_item']['category_id'])->$typeFee / 100, 2);
        $ebayFeeYen                        = round($data['dtb_item']['ebay_fee'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['paypal_fee']    = round($settingInfo->paypal_fee_rate  * $sellPriceYen / 100 + $settingInfo->paypal_fixed_fee, 2);
        $data['dtb_item']['profit']        = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - $data['dtb_item']['ship_fee'] - (float)$data['dtb_item']['buy_price'] * $settingInfo->gift_discount / 100, 2);
    }

    /**
     * calculator profit type yahoo
     * @param  array &$data
     * @return void
     */
    public function calculatorProfitTypeYahoo(&$data)
    {
        $userId                         = Auth::user()->id;
        $exchangeRate                   = $this->exchangeRate->getExchangeRateLatest();
        $settingInfo                    = $this->setting->getSettingOfUser($userId);
        $storeIdOfUser                  = $settingInfo->store_id;
        $stores                         = $this->mtbStore->getAllStore();
        $storeInfo                      = $this->formatStoreInfo($stores);
        $typeFee                        = $storeInfo[$storeIdOfUser];
        $sellPriceYen                   = round($data['dtb_item']['price'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['ebay_fee']   = round($data['dtb_item']['price'] * $this->categoryFee->getCategoryFeeByCategoryId($data['dtb_item']['category_id'])->$typeFee / 100, 2);
        $ebayFeeYen                     = round($data['dtb_item']['ebay_fee'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['paypal_fee'] = round($settingInfo->paypal_fee_rate  * $sellPriceYen / 100, 2);
        $data['dtb_item']['profit']     = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - (float)$data['dtb_item']['buy_price'], 2);
    }

    /**
     * format data item specifics
     * @param  array $itemSpecific
     * @return array
     */
    public function formatDataItemSpecifics($itemSpecific)
    {
        $result = [];
        foreach ($itemSpecific as $item) {
            $specific['name'] = $item['name'];
            $specific['value'] = $item['value'];
            $result[] = $specific;
        }
        return $result;
    }

    /**
     * get image init
     * @param  array $data
     * @return Illuminate\Http\Response
     */
    public function getImageInit($itemId)
    {
        $result['status'] = true;
        $images = $this->itemImage->getImageOfProduct($itemId); 
        $arrayImage = [];
        if (!$images) {
            $result['images'] = $arrayImage;
            return response()->json($result);
        }
        foreach ($images as $key => $image) {
            $arrayItem = explode(".", $image['item_image']);
            $type      = array_pop($arrayItem);
            $item      = [
                'name'      => '',
                'type'      => 'image/' . $type,
                'extension' => $type,
                'file'      => asset($this->pathStorageFile . $image['item_image']),
            ];
            $arrayImage[] = $item;
        }
        $result['images']   = $arrayImage;
        return response()->json($result);
    }
}
