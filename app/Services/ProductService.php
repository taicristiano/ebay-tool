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
                $url = $node->attr('src');
                $arrayItem = explode(".", $url);
                $type = array_pop($arrayItem);
                $item = [
                    'name' => '',
                    'type' => 'image/' . $type,
                    'file' => $url,
                ];
                $arrayImage[] = $item;
            });
        } else {
            // call api amazon
        }
        if (!count($arrayImage)) {
            return response()->json($response);
        }
        $response['status'] = true;
        $response['image'] = $arrayImage;
        $response['data'] = view('admin.product.component.item_yahoo_or_amazon_info', compact('data', 'arrayImage', 'price'))->render();
        return response()->json($response);

    }

    public static function uploadFile(
            $file, 
            $path, 
            $allowType = [], 
            $maxSize = null, 
            $rename = true,
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

    public function calculatorProfit($type)
    {
        // $url = config('api_info.api_ebay_get_item') . $itemId;
        // $result = $this->callApi(null, null, $url, 'get');
        // $response['status'] = false;
        // if ($result['Ack'] == 'Failure') {
        //     return response()->json($response);
        // }
        // $userId = Auth::user()->id;
        // $settingData = $this->setting->getSettingOfUser($userId);
        // $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
        // $data = $this->formatDataEbayInfo($result, $settingData, $settingPolicyData);
        $data = [];
        $response['status'] = true;
        $response['data'] = view('admin.product.component.calculator_info', compact('data'))->render();
        return response()->json($response);
    }
}