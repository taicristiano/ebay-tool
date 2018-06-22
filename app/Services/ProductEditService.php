<?php

namespace App\Services;

use App\Models\Item;
use App\Models\SettingPolicy;
use App\Models\ItemSpecific;
use App\Models\SettingShipping;
use App\Models\ItemImage;
use App\Models\MtbExchangeRate;
use App\Models\MtbStore;
use App\Models\Setting;
use App\Models\CategoryFee;
use App\Models\SettingTemplate;
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
    protected $settingTemplate;

    public function __construct(
        Item $product,
        SettingPolicy $settingPolicy,
        ItemSpecific $itemSpecific,
        SettingShipping $settingShipping,
        ItemImage $itemImage,
        Setting $setting,
        MtbStore $mtbStore,
        CategoryFee $categoryFee,
        SettingTemplate $settingTemplate,
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
        $this->settingTemplate = $settingTemplate;
    }

    /**
     * get data for show page edit product
     * @param  array $item
     * @return array
     */
    public function getDataForShowPageEditProduct($item)
    {
        $data['dtb_item']                        = $item;
        $data['item_id']                         = $item['item_id'];
        $data['dtb_item']['commodity_weight']    = $item['item_weight'];
        $data['dtb_item']['material_quantity']   = $item['pack_material_weight'];
        $data['dtb_item']['length']              = $item['item_length'];
        $data['dtb_item']['height']              = $item['item_height'];
        $data['dtb_item']['width']               = $item['item_width'];
        $data['dtb_item']['item_des']            = $item['item_des'];
        $data['dtb_item']['type']                = $item['original_type'];
        $itemSpecific                            = $this->itemSpecific->getByItemId($item['id']);
        $data['dtb_item_specifics']              = $this->formatDataItemSpecifics($itemSpecific);
        $data['duration']['option']              = $this->product->getDurationOption();
        $data['dtb_setting_policies']            = $this->getDataSettingPolicies();
        $data['istTypeAmazon']                   = $item['original_type'] == $this->product->getOriginTypeAmazon() ? true : false;
        $data['setting_shipping_selected']       = $item['temp_shipping_method']; 
        $settingShippingOption                   = $this->getSettingShippingOfUser($data['dtb_item']);
        $data['setting_shipping_option']         = $settingShippingOption;
        $this->calculatorDetail($data);
        $userId                   = Auth::user()->id;
        $settingTemplate          = $this->settingTemplate->getByUserId($userId);
        $data['setting_template'] = $this->formatSettingTemplate($settingTemplate);
        return $data;
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
