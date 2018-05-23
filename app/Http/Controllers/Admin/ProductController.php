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
        // Session::forget('product-info');
        $data = [];
        if (Session::has('product-info')) {
            $data = $this->productService->formatDataPageProduct(Session::get('product-info')[0]);
        }
        $originType = $this->product->getOriginType();
        return view('admin.product.post', compact('data', 'originType'));
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
            $dataSession = [];
            if (Session::has('product-info')) {
                $dataSession = Session::get('product-info')[0];
                Session::forget('product-info');
            }
            $data = $this->productService->formatDataInsertProductConfirm($data, $dataSession);
            Session::push('product-info', $data);
            $response['status'] = true;
            $response['url'] = route('admin.product.show-confirm');
            return response()->json($response);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * show page confirm
     * @param  Request $request
     * @return Illuminate\Support\Facades\View
     */
    public function showConfirm(Request $request)
    {
        $data = Session::get('product-info')[0];
        if (!$data) {
            return;
        }
        $data = $this->productService->formatDataPageConfirm($data);
        return view('admin.product.confirm', compact('data'));
    }

    /**
     * post product publish
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function postProductPublish(Request $request)
    {
        try {
            return $this->productService->postProductPublish();
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
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

