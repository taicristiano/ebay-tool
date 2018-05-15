<?php

namespace App\Services;

use App\Services\CommonService;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Models\Setting;
use App\Models\SettingPolicy;
use App\Models\Item;
use Goutte\Client;

class ProductService extends CommonService
{
    protected $setting;
    protected $settingPolicy;
    protected $product;

    public function __construct(
        Setting $setting,
        SettingPolicy $settingPolicy,
        Item $product
    ) {
        $this->setting = $setting;
        $this->settingPolicy = $settingPolicy;
        $this->product = $product;
    }

    /**
     * api get session id
     * @return string
     */
    public function apiGetItemEbayInfo($itemId)
    {
        $url = config('api_info.api_ebay_get_item') . $itemId;
        $result = $this->callApi(null, null, $url, 'get');
        $response['status'] = false;
        if ($result['Ack'] == 'Failure') {
            return response()->json($response);
        }
        $userId = Auth::user()->id;
        $settingData = $this->setting->getSettingOfUser($userId);
        $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
        $data = $this->formatDataEbayInfo($result, $settingData, $settingPolicyData);
        $response['status'] = true;
        $response['data'] = view('admin.product.component.item_ebay_info', compact('data'))->render();
        return response()->json($response);
    }

    public function formatDataEbayInfo($data, $settingItem, $settingPolicyData)
    {
        // data dtb_item
        $result['dtb_item'] = [
            'item_name' => $data['Item']['Title'],
            'category_id' => $data['Item']['PrimaryCategoryID'],
            'category_name' => $data['Item']['PrimaryCategoryName'],
            'condition_id' => $data['Item']['ConditionID'],
            'condition_name' => $data['Item']['ConditionDisplayName'],
            'price' => $data['Item']['ConvertedCurrentPrice'],
        ];

        //data dtb_item_specifics
        $result['dtb_item_specifics'] = [];
        foreach ($data['Item']['ItemSpecifics']['NameValueList'] as $specific) {
            $item['name'] = $specific['Name'];
            $item['value'] = $specific['Value'];
            $result['dtb_item_specifics'][] = $item;
        }

        //data dtb_setting
        $settingData['duration']  = $settingItem->duration;
        $settingData['quantity']  = $settingItem->quantity;
        $result['dtb_setting'] = $settingData;

        //data dtb_setting_policies
        $shippingType = [];
        $paymentType = [];
        $returnType = [];
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
            'payment' => $paymentType,
            'return' => $returnType
        ];
        $result['duration']['option'] = $this->product->getDurationOption();
        $result['duration']['value'] = Item::VALUE_DURATION_30_DAY;

        return $result;
    }

    public function apiGetItemYahooOrAmazonInfo($itemId, $type)
    {
        $response['status'] = false;
        if ($type == 'yahoo_auction') {
            $url = config('api_info.api_yahoo_action_info') . $itemId;
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $crawler = $crawler->filterXPath('//*[@id="l-sub"]/div[1]/ul/li[2]/div/dl/dd')->first();
            $price = null;
            if ($crawler->count()) {
                $price = $crawler->text();
            }

            $crawler = $client->request('GET', $url);
            $arrayImage = [];
            $crawler->filterXPath('//*[@id="l-main"]/div/div[1]/div[1]/ul/li/div/img')->each(function ($node) use (&$arrayImage) {
                $arrayImage[] = $node->attr('src');
            });
            dd($arrayImage, $price);

            // $result = $this->callApi(null, null, $url, 'get');
            // if ($result['Ack'] == 'Failure') {
            //     return response()->json($response);
            // }
            // $userId = Auth::user()->id;
            // $settingData = $this->setting->getSettingOfUser($userId);
            // $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
            // $data = $this->formatDataEbayInfo($result, $settingData, $settingPolicyData);
        } else {
            // call api amazon
        }
        $response['status'] = true;
        $response['data'] = view('admin.product.component.item_yahoo_or_amazon_info', compact('data'))->render();
        return response()->json($response);

    }

}