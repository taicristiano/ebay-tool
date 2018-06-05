<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Item;
use App\Http\Requests\CalculateProfitRequest;
use App\Http\Requests\PostProductRequest;
use Illuminate\Support\Facades\Session;

class ProductController extends AbstractController
{
    protected $product;
    protected $productService;
    protected $keyProduct;

    public function __construct(
        ProductService $productService,
        Item $product
    ) {
        $this->productService = $productService;
        $this->product = $product;
        $this->keyProduct = Item::SESSION_KEY_PRODUCT_INFO;
    }

    /**
     * show page post product
     * @return view
     */
    public function showPagePostProduct()
    {
        $hasSettingPolicyData = $this->productService->checkHasSettingPolicyData();
        if (!$hasSettingPolicyData) {
            return view('admin.product.none_policy');
        }
        // Session::forget($this->keyProduct);
        $data = [];
        if (Session::has($this->keyProduct)) {
            $data = $this->productService->formatDataPageProduct(Session::get($this->keyProduct)[0]);
        }
        $conditionIdList = $this->product->getConditionIdList();
        $originType = $this->product->getOriginType();
        return view('admin.product.post', compact('data', 'originType', 'conditionIdList'));
    }


    /**
     * post product confirm
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function postProductConfirm(Request $request)
    {
        try {
            $response['status'] = false;
            $data = $request->all();
            $postProductValidate = PostProductRequest::validateData($data);
            if ($postProductValidate->fails()) {
                $mesageError = $postProductValidate->errors()->messages();
                $response['message_error'] = $this->productService->formatMessageError($mesageError);
                return response()->json($response);
            }
            $dataSession = [];
            if (Session::has($this->keyProduct)) {
                $dataSession = Session::get($this->keyProduct)[0];
                Session::forget($this->keyProduct);
            }
            $data = $this->productService->formatDataInsertProductConfirm($data, $dataSession);
            Session::push($this->keyProduct, $data);
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
        $data = Session::get($this->keyProduct)[0];
        if (!$data) {
            return redirect()->route('admin.product.show-page-post-product');
        }
        $data = $this->productService->formatDataPageConfirm($data);
        return view('admin.product.confirm', compact('data'));
    }

    /**
     * post product publish
     * @return Illuminate\Http\Response
     */
    public function postProductPublish()
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
            return $this->productService->apiGetItemYahooOrAmazonInfo($request->all());
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
        $response['status'] = false;
        try {
            $data = $request->all();
            $calculateProfitValidate = CalculateProfitRequest::validateData($data);
            if ($calculateProfitValidate->fails()) {
                $mesageError = $calculateProfitValidate->errors()->messages();
                $response['message_error'] = $this->productService->formatMessageError($mesageError);
                return response()->json($response);
            }

            return $this->productService->calculatorProfit($data);
        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json($response);
        }
    }

    /**
     * get image init
     * @return Illuminate\Http\Response
     */
    public function getImageInit()
    {
        try {
            $data = Session::get($this->keyProduct)[0];
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
