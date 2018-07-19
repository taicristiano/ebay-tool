<?php

namespace App\Services;

use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use SimpleXMLElement;
use Illuminate\Support\Facades\Auth;
use Goutte\Client;
use Browser\Casper;
use Illuminate\Support\Facades\Log;

class BuyFromYahooAuctionService extends CommonService
{
    protected $product;
    protected $user;
    protected $soldItem;

    public function __construct(
        Item $product,
        User $user,
        SoldItem $soldItem
    ) {
        $this->product  = $product;
        $this->user     = $user;
        $this->soldItem = $soldItem;
    }

    /**
     * buy from yahoo auction
     * @return void
     */
    public function buyFromYahooAuction()
    {
        Log::info('--------------> Start buy from yahoo auction <--------------');
        $arrayItem = ['e288239598', 'm265693400'];
        $arrayItem = ['e288239598', 'm266086582'];
        $soldItems = $this->soldItem->getForMonitoringCrontabSecond();
        foreach ($soldItems as $key => $value) {
            $item = $this->product->findById($value->item_id);
            if ($item && $item['original_type'] == $this->product->getOriginTypeYahooAuction()) {
                if ($this->loginAuction()) {
                    // buy yahoo auction
                    // $item['original_id'] = 'e288239598';
                    if ($this->buyYahooAuction($item['original_id'])) {
                        Log::info('Buy success');
                        Log::info($item['original_id']);
                        $value->auto_buy_flg = $this->soldItem->getFlagAutoByFlgDone();
                    } else {
                        Log::info('Can not buy');
                        Log::info($item['original_id']);
                        $value->auto_buy_flg = $this->soldItem->getFlagAutoByFlgCanNotBuy();
                    }
                    $value->save();
                } else {
                    Log::info('Login fail');
                }
            }
        }
        Log::info('--------------> Finish buy from yahoo auction <--------------');
    }

    /**
     * login auction
     * @return boolean
     */
    public function loginAuction()
    {
        putenv("PHANTOMJS_EXECUTABLE=C:/xampp/htdocs/tool/node_modules/phantomjs/lib/phantom/bin/phantomjs");
$casper = new Casper('C:/xampp/htdocs/tool/node_modules/casperjs/bin/');
        // putenv("PHANTOMJS_EXECUTABLE=/usr/local/bin/phantomjs");
        // $casper = new Casper('/usr/local/bin/');
        $casper->start('https://login.yahoo.co.jp/config/login');
        $casper->setOptions(array(
            'ignore-ssl-errors' => 'yes',
            'cookies-file' => public_path('jsCookies.txt'),
        ));
        $casper->sendKeys('#username', 'cuht2016@gmail.com');
        $casper->click('#btnNext');
        $casper->wait(3000);
        $casper->sendKeys('#passwd', 'miichisoft1234');
        $casper->click('#btnSubmit');
        $casper->run();
        $urlCurrent = $casper->getCurrentUrl();
        if (empty($urlCurrent) || $urlCurrent == 'https://login.yahoo.co.jp/config/login') {
            return false;
        }
        return true;
    }

    /**
     * buy yahoo auction
     * @param  integer $id
     * @return boolean
     */
    public function buyYahooAuction($id)
    {
        // putenv("PHANTOMJS_EXECUTABLE=/usr/local/bin/phantomjs");
        // $casper = new Casper('/usr/local/bin/');
        putenv("PHANTOMJS_EXECUTABLE=C:/xampp/htdocs/tool/node_modules/phantomjs/lib/phantom/bin/phantomjs");
$casper = new Casper('C:/xampp/htdocs/tool/node_modules/casperjs/bin/');
        $casper->start('https://page.auctions.yahoo.co.jp/jp/auction/'.$id);
        $casper->setOptions(array(
            'ignore-ssl-errors' => 'yes',
            'cookies-file' => public_path('jsCookies.txt'),
        ));
        $casper->waitForSelector('.Button--buynow', 3000)->click('.Button--buynow');
        $casper->wait(10000);
        $casper->waitForSelector('#BidModal .js-validator-submit', 3000);
        $casper->click('#BidModal .js-validator-submit');
        $casper->run();
        if ($casper->getCurrentUrl() == 'https://auctions.yahoo.co.jp/jp/show/bid_preview') {
            $casper->click('input.SubmitBox__button.SubmitBox__button--purchase');
            $casper->run();
            if ($casper->getCurrentUrl() == 'https://auctions.yahoo.co.jp/jp/config/placebid') {
                return true;
            }
        }
        return false;
    }
}
