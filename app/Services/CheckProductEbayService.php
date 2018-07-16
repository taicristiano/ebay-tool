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

use App\Models\User;
use App\Models\SoldItem;
use SimpleXMLElement;
use Illuminate\Support\Facades\Auth;
use Goutte\Client;
use Browser\Casper;

class CheckProductEbayService extends CommonService
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

    protected $user;
    protected $soldItem;

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
        MtbExchangeRate $exchangeRate,

        User $user,
        SoldItem $soldItem
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

        $this->user     = $user;
        $this->soldItem = $soldItem;
    }

    /**
     * check on their products sold on ebay have buyers
     * @return void
     */
    public function checkOnTheirProductsSoldOnEbayHaveBuyers()
    {
        $users = $this->user->getUserIsAdminOrGuestAdmin();
        foreach ($users as $user) {
            // Thực hiện call API GetMyeBaySelling. Link: http://developer.ebay.com/devzone/xml/docs/reference/ebay/getmyebayselling.html thực hiện request lấy SoldList                           
            // Lấy list các sản phẩm đã có người mua trên ebay của user đó từ key SoldList                         
            // Lưu sản phẩm đã bán vào bảng dtb_sold_items.                            
            // Nếu sản phẩm đó là bày bán bằng tool của mình (check thông qua item_id) và là sản phẩm liên kết đến yahoo aution thì thực hiện mua sản phẩm đó với giá bán ngay lập tức ở yahoo auction (nếu sản phẩm là không có giá bán ngay lập tức thì bỏ qua)                          
            // Sau khi sản phẩm đó được mua trên yahoo auction thì update trường dtb_sold_items.auto_buy_flg = 1
            $soldList = $this->getMyEbaySelling($user);
            if (empty($soldList)) {
                continue;
            }
            $this->insertSoldItem($soldList);
            dd($soldList, 2);
        }
    }

    /**
     * get my ebay selling
     * @param  array $user
     * @return array
     */
    public function getMyEbaySelling($user)
    {
        return $this->callApiGetMyEbaySelling($user);
    }

    /**
     * call api get my ebay selling
     * @param  array $user
     * @return array
     */
    public function callApiGetMyEbaySelling($user)
    {
        $token  = $user->ebay_access_token;
        $body   = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents"></GetMyeBaySellingRequest>');
        $body->addChild('RequesterCredentials')->addChild('eBayAuthToken', $token);
        $url    = config('api_info.api_common');
        $header = config('api_info.header_api_get_my_ebay_selling');
        $result = $this->callApi($header, $body->asXML(), $url, 'post');
        // if ($result['Ack'] == 'Failure') {
        //     return [];
        // }
        if (!empty($result['SoldList']) && is_array($result['SoldList'])) {
            return $this->formatDataApiGetMyEbaySelling($result['SoldList']);
        }
        return [];
    }

    /**
     * format data api get my ebay selling
     * @param  array $data
     * @return array
     */
    public function formatDataApiGetMyEbaySelling($data)
    {
        $result = [];
        if (!empty($data['OrderTransactionArray'])) {
            foreach ($data['OrderTransactionArray'] as $value) {
                if (!empty($value['Order'])
                    && !empty($value['Order']['TransactionArray']['Transaction']['Item']['ItemID'])) {
                    $itemDetail = $this->product->findByItemId($value['Order']['TransactionArray']['Transaction']['Item']['ItemID']);
                    if (!$itemDetail) {
                        // $item['item_id']            = $itemDetail->id;
                        // $item['type']               = $itemDetail->type;
                        $item['order_id']           = !empty($value['Order']['OrderID']) ? $value['Order']['OrderID'] : '';
                        $item['order_line_id']      = !empty($value['Order']['TransactionArray']['Transaction']['OrderLineItemID']) ? $value['Order']['TransactionArray']['Transaction']['OrderLineItemID'] : '';
                        $item['buyer_postal_code']  = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['BuyerInfo']['ShippingAddress']['PostalCode']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['BuyerInfo']['ShippingAddress']['PostalCode'] : '';
                        $item['buyer_email']        = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['Email']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['Email'] : '';
                        $item['buyer_static_alias'] = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['StaticAlias']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['StaticAlias'] : '';
                        $item['buyer_user_id']      = !empty($value['Order']['TransactionArray']['Transaction']['Buyer']['UserID']) ? $value['Order']['TransactionArray']['Transaction']['Buyer']['UserID'] : '';
                        $item['sold_price']         = !empty($value['Order']['TransactionArray']['Transaction']['Item']['SellingStatus']['CurrentPrice']) ? $value['Order']['TransactionArray']['Transaction']['Item']['SellingStatus']['CurrentPrice'] : '';
                        $item['transaction_id']     = !empty($value['Order']['TransactionArray']['Transaction']['TransactionID']) ? $value['Order']['TransactionArray']['Transaction']['TransactionID'] : '';
                        $item['sold_quantity']      = !empty($value['Order']['TransactionArray']['Transaction']['Item']['SellingStatus']['QuantitySold']) ? $value['Order']['TransactionArray']['Transaction']['Item']['SellingStatus']['QuantitySold'] : '';
                        $item['paid_time']          = !empty($value['Order']['TransactionArray']['Transaction']['PaidTime']) ? $value['Order']['TransactionArray']['Transaction']['PaidTime'] : '';
                        $item['ship_cost']          = !empty($value['Order']['TransactionArray']['Transaction']['Item']['ShippingDetails']['ShippingServiceOptions']['ShippingServiceCost']) ? $value['Order']['TransactionArray']['Transaction']['Item']['ShippingDetails']['ShippingServiceOptions']['ShippingServiceCost'] : '';
                        $item['order_date']         = !empty($value['Order']['TransactionArray']['Transaction']['CreatedDate']) ? $value['Order']['TransactionArray']['Transaction']['CreatedDate'] : '';
                        $result[] = $item;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * save sold item
     * @param  array $soldList
     * @return boolean
     */
    public function saveToTableSlodItem($soldList)
    {
        return $this->soldItem->save($soldList);
    }

    /**
     * buy yahoo auction
     * @param  array $soldList
     * @return void
     */
    public function insertSoldItem(&$soldList)
    {
        foreach ($soldList as &$item) {
            // if ($item['type'] == $this->product->getOriginTypeYahooAuction()) {
                // buy yahoo auction
                if ($this->loginAuction()) {
                    // buyYahooAuction
                    if ($this->buyYahooAuction()) {
                        $item['auto_buy_flg'] = $this->soldItem->getFlagAutoByFlgDone();
                    }
                }
            // }
            unset($item['type']);
            $this->saveToTableSlodItem($soldList);
        }
    }

    public function loginAuction()
    {
        // login
        putenv("PHANTOMJS_EXECUTABLE=/usr/local/bin/phantomjs");
        $casper = new Casper('/usr/local/bin/');
        // cuht2016@gmail.com / miichisoft1234
        $casper->start('https://login.yahoo.co.jp/config/login');
        $casper->sendKeys('#username', 'cuht2016@gmail.com');
        $casper->click('#btnNext');
        // wait for http request success
        $casper->wait(3000);
        $casper->sendKeys('#passwd', 'miichisoft1234');
        $casper->click('#btnSubmit');
        $casper->run();
        if ($casper->getCurrentUrl() == 'https://login.yahoo.co.jp/config/login') {
            return false;
        }
        return true;
    }

    public function buyYahooAuction()
    {
        putenv("PHANTOMJS_EXECUTABLE=/usr/local/bin/phantomjs");
        $casper = new Casper('/usr/local/bin/');
        // cuht2016@gmail.com / miichisoft1234
        $casper->start('https://page.auctions.yahoo.co.jp/jp/auction/m265693400');
        // $casper->sendKeys('#username', 'cuht2016@gmail.com');
        // wait for httprequest success
        $casper->waitForSelector('.Button--buynow', 3000)->click('.Button--buynow');
        $casper->wait(10000);
        // $casper->sendKeys('#passwd', 'miichisoft1234');
        $casper->waitForSelector('.js-validator-submit', 3000);
        $casper->click('.js-validator-submit');
        $casper->run();
        echo $casper->getHTML();
        die;
        // die;
        dd($casper);
    }
}
