<?php

return [
    'api_common'                      => 'https://api.sandbox.ebay.com/ws/api.dll',
    'header_api_fetch_get_session_id' => [
        'Content-Type'                   => 'text/xml',
        'X-EBAY-API-SITEID'              => '0',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'GetSessionID',
        'X-EBAY-API-APP-NAME'            => 'YOURSCOM--SBX-8e043dc2e-701632f2',
        'X-EBAY-API-DEV-NAME'            => 'ec1441f1-6e0c-4220-8f3a-875067910ded',
        'X-EBAY-API-CERT-NAME'           => 'SBX-e043dc2e8a00-3902-492f-90ce-5aba'
    ],
    'body_request_api_fetch_get_session_id' => '<?xml version="1.0" encoding="utf-8" ?><GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents"><ErrorLanguage>en_US</ErrorLanguage><WarningLevel>High</WarningLevel><RuName>YOURS_COMPANY-YOURSCOM--SBX-8-kkgfpvrbz</RuName></GetSessionIDRequest>',
    'header_api_fetch_token' => [
        'X-EBAY-API-SITEID'              => '0',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'FetchToken',
        'X-EBAY-API-APP-NAME'            => 'YOURSCOM--SBX-8e043dc2e-701632f2',
        'X-EBAY-API-DEV-NAME'            => 'ec1441f1-6e0c-4220-8f3a-875067910ded',
        'X-EBAY-API-CERT-NAME'           => 'SBX-e043dc2e8a00-3902-492f-90ce-5aba',
    ],
    'body_request_api_fetch_token' => '<?xml version="1.0" encoding="utf-8" ?><FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents"><ErrorLanguage>en_US</ErrorLanguage><WarningLevel>High</WarningLevel><SessionID>session_id</SessionID></FetchTokenRequest>',
    'api_get_policy'               => 'http://svcs.sandbox.ebay.com/services/selling/v1/SellerProfilesManagementService',
    'header_api_get_policy'        => [
        'X-EBAY-SOA-OPERATION-NAME' => 'getSellerProfiles',
        'X-EBAY-SOA-SECURITY-TOKEN' => '',
        'X-EBAY-SOA-GLOBAL-ID'      => 'EBAY-US',
    ],
    'url_redirect_get_session_id'   => 'https://signin.sandbox.ebay.com/ws/eBayISAPI.dll?SignIn&runame=YOURS_COMPANY-YOURSCOM--SBX-8-kkgfpvrbz&SessID=',
    'api_ebay_get_item'             => 'http://open.api.sandbox.ebay.com/shopping?callname=GetSingleItem&responseencoding=XML&appid=YOURSCOM--SBX-8e043dc2e-701632f2&siteid=0&version=967&IncludeSelector=Details,ItemSpecifics&ItemID=',
    'api_yahoo_action_info'         => 'https://page.auctions.yahoo.co.jp/jp/auction/',
    'api_amazon_get_item'           => 'https://mws.amazonservices.jp',
    'regex_get_price_yahoo_auction' => '//*[@id="l-sub"]/div[1]/ul/li[2]/div/dl/dd',
    'regex_get_image_yahoo_auction' => '//*[@id="l-main"]/div/div[1]/div[1]/ul/li/div/img',
    'market_place_id_amazon'        => 'A1VC38T7YXB528',
    'ebay_url'                      => 'https://www.ebay.com/itm/',
    'header_api_end_item' => [
        'Content-Type'                   => 'text/xml',
        'X-EBAY-API-SITEID'              => '0',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'EndItems',
    ],
];
