<?php

namespace App\Http\Controllers\Admin;
use Log;
use Goutte\Client;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Item;
use App\Http\Requests\UpdateProfitRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends AbstractController
{
    protected $product;
    protected $productService;

    public function __construct(
        ProductService $productService,
        Item $product
    ) {
        $this->productService = $productService;
        $this->product = $product;
    }

    /**
     * show page post product
     * @return view
     */
    public function showPagePostProduct()
    {
        // Storage::move('/public/test/tainhot.png', '/public/test/tainhot1.png');

        // dd($this->productService->uploadTesst(Session::get('product-info')[0]['file_7']));
        // Session::forget('product-info');
        $data = [];
        if (Session::has('product-info')) {
            // $data = Session::get('product-info')[0];
            $data = $this->productService->formatDataPageProduct(Session::get('product-info')[0]);
        }
        $originType = $this->product->getOriginType();
        return view('admin.product.post', compact('data', 'originType'));
    }

    /**
     * api get item ebay info
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function apiGetItemEbayInfo(Request $request)
    {
        try {
            return $this->productService->apiGetItemEbayInfo($request->item_id);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * api get item yahoo or amazon info
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function apiGetItemYahooOrAmazonInfo(Request $request)
    {
        try {
            return $this->productService->apiGetItemYahooOrAmazonInfo($request->item_id, $request->type);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * calculator profit
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function calculatorProfit(Request $request)
    {
        try {
            return $this->productService->calculatorProfit($request->all());
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * update profit
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function updateProfit(Request $request)
    {
        $response['status'] = false;
        try {
            $data = $request->all();
            $updateProfitValidate = UpdateProfitRequest::validateData($data);
            if ($updateProfitValidate->fails()) {
                $response['message_error'] = $updateProfitValidate->errors();
                return response()->json($response);
            }
            return $this->productService->updateProfit($data);
        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json($response);
        }
    }

    /**
     * post product confirm
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function postProductConfirm(Request $request)
    {
        try {
            $data = $request->all();
            $data = $this->productService->formatDataInsertProductConfirm($data);
            Session::forget('product-info');
            Session::push('product-info', $data);
            $response['status'] = true;
            $response['url'] = route('admin.product.show-confirm');
            return response()->json($response);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
        // http://localhost/ebayTool/public/storage/test/rtRt2_1526574828.png
    }

    /**
     * show page confirm
     * @param  Request $request
     * @return Illuminate\Support\Facades\View
     */
    public function showConfirm(Request $request)
    {
        // $this->productService->uploadTesst(Session::get('product-info')[0]['file_7']);
        $data = Session::get('product-info')[0];
        if (!$data) {
            return;
        }
        $data = $this->productService->formatDataPageConfirm($data);
            dd($data);
        
        return view('admin.product.confirm', compact('data'));
    }

    /**
     * get image init
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function getImageInit(Request $request)
    {
        try {
            $data = Session::get('product-info')[0];
            if (!$data) {
                throw new Exception();
            }
            return $this->productService->getImageInit($data);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }
}

