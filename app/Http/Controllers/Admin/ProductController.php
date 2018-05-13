<?php

namespace App\Http\Controllers\Admin;
use Log;
use Goutte\Client;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends AbstractController
{
    public function __construct(
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }

    /**
     * show page post product
     * @return view
     */
    public function showPagePostProduct()
    {
        // try {
        //     // https://github.com/coopTilleuls/amazon-mws-products/tree/master/src/MarketplaceWebServiceProducts/Model
        //     // http://jumps-world.com/amazon-api-programing/amazonapi/amazonmwsapi%E3%82%92%E6%89%8B%E3%81%A3%E5%8F%96%E3%82%8A%E6%97%A9%E3%81%8F%E5%8B%95%E3%81%8B%E3%81%99%E6%96%B9%E6%B3%95-%E5%85%B6%E3%81%AE3/
        //     dd($this->productService->aptGet());
        //     $url = 'https://page.auctions.yahoo.co.jp/jp/auction/c642534441';
        //     $client = new Client();
        //     $crawler = $client->request('GET', $url);
        //     $crawler = $crawler->filterXPath('//*[@id="l-sub"]/div[1]/ul/li[2]/div/dl/dd')->first();
        //     $price = null;
        //     if ($crawler->count()) {
        //         $price = $crawler->text();
        //     }

        //     $crawler = $client->request('GET', $url);
        //     $arrayImage = [];
        //     $crawler->filterXPath('//*[@id="l-main"]/div/div[1]/div[1]/ul/li/div/img')->each(function ($node) use (&$arrayImage) {
        //         $arrayImage[] = $node->attr('src');
        //     });
        //     dd($arrayImage);
        //     Log::info('Exchange rate command success');
        // } catch (Exception $e) {
        //     dd(2222);
        //     Log::info('Exchange rate command error');
        // }
        return view('admin.product.post');
    }

    public function apiGetItemEbayInfo(Request $request)
    {
        try {
            return $this->productService->apiGetItemEbayInfo($request->item_id);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
        $response['status'] = false;
        return view('admin.product.post', compact('data'));
    }
}

