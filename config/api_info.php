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
    'market_place_id_amazon'        => 'A1VC38T7YXB528'

    // for addFixedPriceItem
    'header_api_add_fixed_price_item' => [
        'Content-Type'                   => 'text/xml',
        'X-EBAY-API-COMPATIBILITY-LEVEL' => '967',
        'X-EBAY-API-CALL-NAME'           => 'AddFixedPriceItem',
        'X-EBAY-API-SITEID'              => 0,
    ],
    'sandbox_user_token' => "AgAAAA**AQAAAA**aAAAAA**36sWWw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4agD5KDpwSdj6x9nY+seQ**W50EAA**AAMAAA**U3b/w0pgbwIxDcpuI5ctSt1l5Vqd/MQ7rbwZY7B64rPXHsmFw+4pOaB11swhHE0pde2Ija6iAn/Ycnu+mYa9zds9wV0D2xDfrYbGNnTYOxr6k2wde60IVsMRPJUZKSA3hA2T16CCsHZRVg/D1G1cf0mqmAv6Im62y1dLuT7peQMH9cZoMZUb6ezBascw4MviTooO6Y70FKc+WH87oMN9jzq78P5as6MDJ/amQa/ZdSdPsL0+6lKFlEE9/2BuA6QDDAl7TL+2bgaQeuQgBvUYSHnAw0yfKF8BLpKYBmoytS+mSxB6gp9sbniuMbmOp99edx+YCR6ZHAeMQkrzwGN6WqfERY87a830KdJ7LQntiDH/1bYCLLHpn7jeuRgXV53M77/pn/7PYL+k77EW61P84qGpCW7RLrnU5ikhKCnQ6BlGsi4LSp0FSR+uG7vf7tRabRUB4KTrexmY0DZ3nd4TUa3njBrjZgElH0/QfoDnXB6L/waUQfJ5SfyHgbYzaKR4UV90BRy7l1yDOo3XtLOzS4tX7GgcO2xqrz8CcCzMUS0ANEvVmZQ44qsZZdjJLbBJZrkcmYdE645A5fHq3Ow/GmvwK/Pe6pC5RSRuzj4VjdHx4yyqRncg8metkIMJ72EywSwikgcpXJtNKIEE1TJVfanMewB4zxMh6+d3HQ2teTGA8WK6YDqtzOwqhSOabeFfVcdmAFmZLWps8oOQCYOkqyBhOUyBxTCoIq/+0sKX8OXE7PMP5m/L8XBdfJxgiQjy",
];
