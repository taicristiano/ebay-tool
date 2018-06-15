<?php

namespace App\Services;

use Excel;
use Illuminate\Support\Facades\Lang;
use App\Models\Item;
use App\Models\SettingPolicy;
use Illuminate\Support\Facades\Auth;
use SimpleXMLElement;

class ProductListService extends CommonService
{
    protected $product;
    protected $settingPolicy;

    public function __construct(
        Item $product,
        SettingPolicy $settingPolicy
    ) {
        $this->product         = $product;
        $this->settingPolicy   = $settingPolicy;
    }

    /**
     * generate column export csv
     * @return array
     */
    public static function generateColumnExportCsv()
    {
        return [
            Lang::get('view.original_id'),
            Lang::get('view.item_id'),
            Lang::get('view.original_type'),
            Lang::get('view.product_name'),
            Lang::get('view.category_id'),
            Lang::get('view.category'),
            Lang::get('view.JAN/UPC'),
            Lang::get('view.condition_id'),
            Lang::get('view.condition_name'),
            Lang::get('view.condition_des'),
            Lang::get('view.sell_price'),
            Lang::get('view.duration'),
            Lang::get('view.quantity'),
            Lang::get('view.shipping_policy'),
            Lang::get('view.return_policy'),
            Lang::get('view.day_of_sale'),
            Lang::get('view.height'),
            Lang::get('view.width'),
            Lang::get('view.length'),
            Lang::get('view.material_quantity'),
            Lang::get('view.buy_price'),
            Lang::get('view.ship_fee'),
            Lang::get('view.last_mornitoring_date'),
            Lang::get('view.created_at'),
        ];
    }

    /**
     * generate data export csv
     * @param  array $data
     * @return array
     */
    public function generateDataExportCsv($data)
    {
        $userId            = Auth::user()->id;
        $originType        = $this->product->getOriginType();
        $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
        $results           = [];
        if (count($data)) {
            foreach ($data as $key => $item) {
                $row = [
                    $item['original_id'],
                    $item['item_id'],
                    $originType[$item['original_type']],
                    $item['item_name'],
                    $item['category_id'],
                    $item['category_name'],
                    $item['jan_upc'],
                    $item['condition_id'],
                    $item['condition_name'],
                    $item['condition_des'],
                    $item['price'] . ' ' . Lang::get('view.usd'),
                    $item['duration'],
                    $item['quantity'],
                    $this->getPolicyNameById(!empty($item['shipping_policy_id']) ? $item['shipping_policy_id'] : '', $settingPolicyData),
                    $this->getPolicyNameById(!empty($item['return_policy_id']) ? $item['return_policy_id'] : '', $settingPolicyData),
                    self::formatDate('Y/m/d', $item['day_of_sale']),
                    $item['item_height'] ? $item['item_height'] . ' ' . Lang::get('view.cm') : null,
                    $item['item_width'] ? $item['item_width'] . ' ' . Lang::get('view.cm') : null,
                    $item['item_length'] ? $item['item_length'] . ' ' . Lang::get('view.cm') : null,
                    $item['pack_material_weight'] ? $item['pack_material_weight'] . ' ' . Lang::get('view.g') : null,
                    $item['buy_price'] ? $item['buy_price'] . ' ' . Lang::get('view.man') : null,
                    $item['ship_fee'] ? $item['ship_fee'] . ' ' . Lang::get('view.man') : null,
                    self::formatDate('Y/m/d', $item['last_mornitoring_date']),
                    self::formatDate('Y/m/d', $item['created_at']),
                ];
                $results[] = $row;
            }
        }
        return $results;
    }

    /**
     * export csv
     * @param  integer $userId
     * @return file
     */
    public function exportCsv($userId)
    {
        $data        = $this->product->getDataExportCsv($userId);
        $data        = $this->generateDataExportCsv($data);
        $dateNow     = date('Ymd h:i:s');
        $fileName    = Lang::get('view.product_list') . '_' . $dateNow;
        $columnTitle = $this->generateColumnExportCsv();
        return $this->excuteExportCsv($fileName . ".csv", $columnTitle, $data);
    }

    /**
     * update item
     * @param  array $data
     * @return boolean
     */
    public function updateItem($data)
    {
        unset($data['_token']);
        $id = $data['id'];
        unset($data['id']);
        return $this->product->updateItem($id, $data);
    }

    public function endItem($data)
    {
        $token = Auth::user()->ebay_access_token;
        $body = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><EndItemsRequest xmlns="urn:ebay:apis:eBLBaseComponents"></EndItemsRequest>');
        $body->addChild('RequesterCredentials')->addChild('eBayAuthToken', $token);
        foreach ($data['item_ids'] as $key => $item) {
            $endItem = $body->addChild('EndItemRequestContainer');
            $endItem->addChild('EndingReason', 'LostOrBroken');
            $endItem->addChild('ItemID', $item);
        }
        $url    = config('api_info.api_common');
        $header = config('api_info.header_api_end_item');
        $result = $this->callApi($header, $body->asXML(), $url, 'post');
        if ($result['Ack'] == 'Failure') {
            return false;
        }
        if ($this->product->endListItem($data['item_ids']) !== false) {
            return true;
        }
        return false;
    }
}
