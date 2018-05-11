<?php

namespace App\Http\Controllers\Admin;
use Log;
use Goutte\Client;

class ProductController extends AbstractController
{
    /**
     * show page post product
     * @return view
     */
    public function showPagePostProduct()
    {
        // $url = 'https://www.gaitameonline.com/rateaj/getrate';
        // $client = new Client();
        // $crawler = $client->request('GET', $url);
        // $crawler->filter('p')->each(function ($node) {
        //     $data = json_decode($node->text());
        //     dd($node->text());
        //     foreach ($data->quotes as $key => $value) {
        //         if ($value->currencyPairCode == 'USDJPY') {
        //             $dataExchangeRate['exchange_date'] = date('Y-m-d H:i:s');
        //             $dataExchangeRate['rate'] = $value->ask;
        //             $exchangeRate = $this->exchangeRate;
        //             $exchangeRate->fill($dataExchangeRate);
        //             $exchangeRate->save();
        //         }
        //     }
        // });
        try {
            $url = 'https://page.auctions.yahoo.co.jp/jp/auction/c642534441';
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
            Log::info('Exchange rate command success');
        } catch (Exception $e) {
            dd(2222);
            Log::info('Exchange rate command error');
        }
        return view('admin.product.post');
    }
}

