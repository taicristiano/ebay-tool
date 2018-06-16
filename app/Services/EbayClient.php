<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SettingPolicy;
use Auth;
use Exception;
use GuzzleHttp\Client;
use SimpleXMLElement;

class EbayClient extends CommonService
{
    const PAYMENT_METHOD   = 'PayPal';
    const RESPONSE_SUCCESS = 'Success';
    const RESPONSE_ERROR   = 'Failure';

    /**
     * add fixed price item
     * @param array $data
     * @return integer ItemID
     */
    public function addFixedPriceItem($data)
    {
        $xmlBody = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><AddFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents"></AddFixedPriceItemRequest>');

        if (!$ebayAccessToken = Auth::user()->ebay_access_token) {
            throw new Exception("Access token not found.");
        }
        $xmlBody->addChild('RequesterCredentials')->addChild('eBayAuthToken', $ebayAccessToken);
        $itemNode = $xmlBody->addChild('Item');

        // add base params
        $itemNode->addChild('Title', htmlspecialchars($data['dtb_item']['item_name']));
        $itemNode->addChild('Description', htmlspecialchars($data['dtb_item']['condition_des']));
        $itemNode->addChild('ConditionID', $data['dtb_item']['condition_id']);
        $itemNode->addChild('ConditionDisplayName', htmlspecialchars($data['dtb_item']['condition_name']));
        $itemNode->addChild('StartPrice', $data['dtb_item']['price']);
        $itemNode->addChild('ListingDuration', $data['dtb_item']['duration']);
        $itemNode->addChild('Quantity', $data['dtb_item']['quantity']);
        $itemNode->addChild('Currency', 'USD');
        $itemNode->addChild('Country', 'US');
        $itemNode->addChild('Location', 'JP');

        // add  payment method
        if (!$paypalEmail = Setting::getPaymentEmailByUserId(Auth::id())) {
            throw new Exception("PayPal email not found.");
        }
        $itemNode->addChild('PaymentMethods', 'PayPal');
        $itemNode->addChild('PayPalEmailAddress', $paypalEmail);

        // add category
        $categoryNode = $itemNode->addChild('PrimaryCategory');
        $categoryNode->addChild('CategoryID', $data['dtb_item']['category_id']);
        $categoryNode->addChild('CategoryName', htmlspecialchars($data['dtb_item']['category_name']));

        // add item specifics
        $itemSpecificsNode = $itemNode->addChild('ItemSpecifics');
        foreach ($data['dtb_item_specifics'] as $item) {
            $nameValueList = $itemSpecificsNode->addChild('NameValueList');
            $nameValueList->addChild('Name', $item['name']);
            $nameValueList->addChild('Value', $item['value']);
        }

        // add return policy
        if (empty($data['dtb_item']['return_policy_id']) || !$returnPolicyData = SettingPolicy::getPolicyContent($data['dtb_item']['return_policy_id'])) {
            throw new Exception('ReturnPolicy not found.');
        }
        $returnPolicyNode = $itemNode->addChild('ReturnPolicy');
        $returnPolicyNode->addChild('ReturnsAcceptedOption', $returnPolicyData['returnPolicyInfo']['returnsAcceptedOption']);
        $returnPolicyNode->addChild('RefundOption', $returnPolicyData['returnPolicyInfo']['refundOption']);
        $returnPolicyNode->addChild('ReturnsWithinOption', $returnPolicyData['returnPolicyInfo']['returnsWithinOption']);
        $returnPolicyNode->addChild('ShippingCostPaidByOption', $returnPolicyData['returnPolicyInfo']['shippingCostPaidByOption']);

        // add shipping policy
        if (empty($data['dtb_item']['shipping_policy_id']) || !$shippingData = SettingPolicy::getPolicyContent($data['dtb_item']['shipping_policy_id'])) {
            throw new Exception('ShippingPolicy not found.');
        }
        $itemNode->addChild('DispatchTimeMax', $shippingData['shippingPolicyInfo']['dispatchTimeMax']);
        $shippingNode = $itemNode->addChild('ShippingDetails');
        if (isset($shippingData['shippingPolicyInfo']['domesticShippingPolicyInfoService']['shippingService'])) {
            $shippingData['shippingPolicyInfo']['domesticShippingPolicyInfoService'] = [$shippingData['shippingPolicyInfo']['domesticShippingPolicyInfoService']];
        }
        foreach ($shippingData['shippingPolicyInfo']['domesticShippingPolicyInfoService'] as $domesticShippingPolicyInfoService) {
            $shippingServiceNode = $shippingNode->addChild('ShippingServiceOptions');
            $shippingServiceNode->addChild('ShippingService', $domesticShippingPolicyInfoService['shippingService']);
            $shippingServiceNode->addChild('FreeShipping', $domesticShippingPolicyInfoService['freeShipping']);
            $shippingServiceNode->addChild('ShippingServiceAdditionalCost', $domesticShippingPolicyInfoService['shippingServiceAdditionalCost']);
        }

        // add ShippingPackageDetails
        $calculatedShippingRateNode = $itemNode->addChild('ShippingPackageDetails');
        $calculatedShippingRateNode->addChild('PackageLength', round($data['dtb_item']['length'] / 2.54, 2));
        $calculatedShippingRateNode->addChild('PackageWidth', round($data['dtb_item']['width'] / 2.54, 2));

        // make request
        $response = (new Client)->request('POST', config('api_info.api_common'), [
            'headers' => config('api_info.header_api_add_fixed_price_item'),
            'body'    => $xmlBody->asXML(),
        ]);

        $response = simplexml_load_string($response->getBody()->getContents());
        // if have errors
        if ((string) $response->Ack == static::RESPONSE_ERROR) {
            throw new Exception((string) $response->Errors->ShortMessage, (int) $response->Errors->ErrorCode);
        }
        return (int) $response->ItemID;
    }
}
