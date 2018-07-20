<?php
return [
    'api_common'                      => env('API_COMMON'),
    'header_api_fetch_get_session_id' => [
        'Content-Type'                   => 'text/xml',
        'X-EBAY-API-SITEID'              => '0',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'GetSessionID',
        'X-EBAY-API-APP-NAME'            => env('X_EBAY_API_APP_NAME'),
        'X-EBAY-API-DEV-NAME'            => env('X_EBAY_API_DEV_NAME'),
        'X-EBAY-API-CERT-NAME'           => env('X_EBAY_API_CERT_NAME'),
    ],
    'body_request_api_fetch_get_session_id' => '<?xml version="1.0" encoding="utf-8" ?><GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents"><ErrorLanguage>en_US</ErrorLanguage><WarningLevel>High</WarningLevel><RuName>' . env('RU_NAME') . '</RuName></GetSessionIDRequest>',
    'header_api_fetch_token' => [
        'X-EBAY-API-SITEID'              => '0',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'FetchToken',
        'X-EBAY-API-APP-NAME'            => env('X_EBAY_API_APP_NAME'),
        'X-EBAY-API-DEV-NAME'            => env('X_EBAY_API_DEV_NAME'),
        'X-EBAY-API-CERT-NAME'           => env('X_EBAY_API_CERT_NAME'),
    ],
    'body_request_api_fetch_token' => '<?xml version="1.0" encoding="utf-8" ?><FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents"><ErrorLanguage>en_US</ErrorLanguage><WarningLevel>High</WarningLevel><SessionID>session_id</SessionID></FetchTokenRequest>',
    'api_get_policy'               => env('API_GET_POLICY'),
    'header_api_get_policy'        => [
        'X-EBAY-SOA-OPERATION-NAME' => 'getSellerProfiles',
        'X-EBAY-SOA-SECURITY-TOKEN' => '',
        'X-EBAY-SOA-GLOBAL-ID'      => 'EBAY-US',
    ],
    'url_redirect_get_session_id'   => env('URL_REDIRECT_GET_SESSION_ID'),
    'api_ebay_get_item'             => env('API_EBAY_GET_ITEM'),
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
    'header_api_change_item' => [
        'Content-Type'                   => 'text/xml',
        'X-EBAY-API-SITEID'              => '0',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'ReviseInventoryStatus',
    ],

    // for addFixedPriceItem
    'header_api_add_fixed_price_item' => [
        'Content-Type'                   => 'text/xml',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'AddFixedPriceItem',
        'X-EBAY-API-SITEID'              => 0,
    ],

    'header_api_get_my_ebay_selling' => [
        'Content-Type'                   => 'text/xml',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'GetMyeBaySelling',
        'X-EBAY-API-SITEID'              => 0,
    ],
];
