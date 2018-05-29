<?php

namespace App\Http\Controllers\Admin;

use Log;
use Goutte\Client;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Item;
use App\Http\Requests\UpdateProfitRequest;
use App\Http\Requests\PostProductRequest;
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
        $originType = $this->product->getOriginType();
        return view('admin.product.post', compact('data', 'originType'));
    }

    public function XML2Array(SimpleXMLElement $parent)
    {
        $array = array();

        foreach ($parent as $name => $element) {
            ($node = & $array[$name])
                && (1 === count($node) ? $node = array($node) : 1)
                && $node = & $node[];

            $node = $element->count() ? $this->XML2Array($element) : trim($element);
        }

        return $array;
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
                $response['message_error'] = $postProductValidate->errors();
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
