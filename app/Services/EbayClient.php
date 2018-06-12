<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\SettingPolicy;
use SimpleXMLElement;
use Auth;

class EbayClient extends CommonService
{
	const PAYMENT_METHOD = 'PayPal';

    public $data = array(
        'dtb_item'           => array(
            'item_name'               => 'Miichisoft Item’s', // added
            'category_id'             => '112529', // added
            'jan_upc'                 => null,
            'condition_id'            => '1000', // added
            'condition_des'           => 'a',
            'price'                   => '249.99', // added
            'duration'                => 'Days_30', // added
            'quantity'                => '1', // added
            'shipping_policy_id'      => '10',
            'payment_policy_id'       => '9',
            'commodity_weight'        => '2200',
            'length'                  => '46.5',  // added
            'height'                  => '18.5',
            'width'                   => '29', // added
            'material_quantity'       => null,
            'setting_shipping_option' => '1',
            'ship_fee'                => '2400',
            'ebay_fee'                => '25',
            'paypal_fee'              => '1025.73',
            'buy_price'               => '100',
            'profit'                  => '21117.42',
            'original_id'             => 'B01GUPMJMA',
            'item_id'                 => '192375777401', // added
            'type'                    => '2',
            'category_name'           => 'Consumer Electronics:Portable Audio & Headphones:Headphones', // added
            'condition_name'          => 'New', // added
        ),
        'dtb_item_specifics' => array(  // added
            0 => array(
                'name'  => 'Brand',
                'value' => 'Beats by Dr. Dre',
            ),
            1 => array(
                'name'  => 'MPN',
                'value' => 'MNET2LL/A',
            ),
            2 => array(
                'name'  => 'UPC',
                'value' => '190198105455',
            ),
            3 => array(
                'name'  => 'Manufacturer Warranty',
                'value' => 'Yes',
            ),
            4 => array(
                'name'  => 'Warranty - Parts',
                'value' => '1 year',
            ),
            5 => array(
                'name'  => 'Warranty - Labor',
                'value' => '1 year',
            ),
        ),
        'number_file'        => '1',
        'istTypeAmazon'      => true,
        'url_preview_0'      => 'http://miichi-dev02.ap-northeast-1.elasticbeanstalk.com/storage/upload/item-images/B01GUPMJMA_0.jpg',
        'file_name_0'        => 'B01GUPMJMA_0.jpg',
    );

    /**
     * add fixed price item
     * @param array $data
     */
    public function addFixedPriceItem($data)
    {
        $data = $this->data;

        $xmlBody = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><AddFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents"></AddFixedPriceItemRequest>');
        
        $xmlBody->addChild('RequesterCredentials')->addChild('eBayAuthToken', config('api_info.sandbox_user_token'));
        $itemNode = $xmlBody->addChild('Item');
        
        $itemNode->addChild('Title', $data['dtb_item']['item_name']);
        $itemNode->addChild('Description', $data['dtb_item']['item_name']);
        $itemNode->addChild('ConditionID', $data['dtb_item']['condition_id']);
        $itemNode->addChild('ConditionDisplayName', $data['dtb_item']['condition_name']);
        $itemNode->addChild('StartPrice', $data['dtb_item']['price']);
        $itemNode->addChild('ListingDuration', $data['dtb_item']['duration']);
        $itemNode->addChild('Quantity', $data['dtb_item']['quantity']);
        $itemNode->addChild('Currency', 'USD');
        $itemNode->addChild('Country', 'US');
        $itemNode->addChild('Location', 'JP');
        $itemNode->addChild('PaymentMethods', 'PayPal');
        $itemNode->addChild('PayPalEmailAddress', Auth::user()->email);

        // add category
        $categoryNode = $itemNode->addChild('PrimaryCategory');
        $categoryNode->addChild('CategoryID', $data['dtb_item']['category_id']);
        // $categoryNode->addChild('CategoryName', $data['dtb_item']['category_name']);

        // add item specifics
        $itemSpecificsNode = $itemNode->addChild('ItemSpecifics');
        foreach ($data['dtb_item_specifics'] as $item) {
            $nameValueList = $itemSpecificsNode->addChild('NameValueList');
            $nameValueList->addChild('Name', $item['name']);
            $nameValueList->addChild('Value', $item['value']);
        }

        // add return policy
        $returnPolicyData = SettingPolicy::getPolicyContent($data['dtb_item']['return_policy_id']);
        $returnPolicyNode = $itemNode->addChild('ReturnPolicy');
        $returnPolicyNode->addChild('ReturnsAcceptedOption', 'ReturnsAccepted');
        $returnPolicyNode->addChild('RefundOption', 'MoneyBack');
        $returnPolicyNode->addChild('ReturnsWithinOption', 'Days_30');
        $returnPolicyNode->addChild('ShippingCostPaidByOption', 'Buyer');

        // add shipping
        $shippingData = SettingPolicy::getPolicyContent($data['dtb_item']['shipping_policy_id']);
        $shippingNode = $itemNode->addChild('ShippingDetails');
        $shippingServiceNode = $shippingNode->addChild('ShippingServiceOptions');
        $shippingServiceNode->addChild('ShippingServicePriority', '1');
        $shippingServiceNode->addChild('ShippingService', 'UPSGround');
        $shippingServiceNode->addChild('FreeShipping', 'true');
        $shippingServiceNode->addChild('ShippingServiceAdditionalCost', '0.00');


        // add ShippingPackageDetails
        $calculatedShippingRateNode = $itemNode->addChild('ShippingPackageDetails');
        $calculatedShippingRateNode->addChild('PackageLength', $data['dtb_item']['length']);
        $calculatedShippingRateNode->addChild('PackageWidth', $data['dtb_item']['width']);
        // $calculatedShippingRateNode->addChild('PackageDepth', $data['dtb_item']['?']);

        // add shipping services
     //   	<ShippingDetails>
	    //   <ShippingType>Flat</ShippingType>
	    //   <ShippingServiceOptions>
	    //     <ShippingServicePriority>1</ShippingServicePriority>
	    //     <ShippingService>UPSGround</ShippingService>
	    //     <FreeShipping>true</FreeShipping>
	    //     <ShippingServiceAdditionalCost currencyID="USD">0.00</ShippingServiceAdditionalCost>
	    //   </ShippingServiceOptions>
	    // </ShippingDetails>
        
        // dd($xmlBody->asXML());

        // make request
        $client   = new Client;
        $response = $client->request('POST', config('api_info.api_common'), [
            'headers' => config('api_info.header_api_add_fixed_price_item'),
            'body'    => $xmlBody->asXML(),
        ]);

        dd($response->getBody()->getContents());
    }

    /**
     * array to xml data
     * @param  array $data
     * @param  object SimpleXMLElement $xmlData
     * @return mixed
     */
    private function arrayToXml($data, &$xmlData)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }
            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    /**
     * parse xml body
     * @param  array $data
     * @param  string $data
     * @return xml
     */
    private function parseBody($body, $rootElement)
    {
        $xmlData = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?>' . $rootElement);
        $this->arrayToXml($body, $xmlData);
        return $xmlData->asXML();
    }
}
