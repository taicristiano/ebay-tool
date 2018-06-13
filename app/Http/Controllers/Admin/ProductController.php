<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\ProductPostService;
use App\Services\ProductListService;
use App\Models\Item;
use App\Models\CategoryFee;
use App\Models\MtbExchangeRate;
use App\Http\Requests\CalculateProfitRequest;
use App\Http\Requests\PostProductRequest;
use Illuminate\Support\Facades\Session;
use App\Models\ItemImage;

class ProductController extends AbstractController
{
    protected $product;
    protected $category;
    protected $productPostService;
    protected $keyProduct;
    protected $itemImage;
    protected $productListService;
    protected $exchangeRate;

    public function __construct(
        ProductPostService $productPostService,
        Item $product,
        CategoryFee $category,
        ProductListService $productListService,
        ItemImage $itemImage,
        MtbExchangeRate $exchangeRate
    ) {
        $this->productPostService = $productPostService;
        $this->product            = $product;
        $this->category           = $category;
        $this->keyProduct         = Item::SESSION_KEY_PRODUCT_INFO;
        $this->itemImage          = $itemImage;
        $this->productListService = $productListService;
        $this->exchangeRate       = $exchangeRate;
    }

    /**
     * show page post product
     * @return view
     */
    public function showPagePostProduct()
    {
        $hasSettingPolicyData = $this->productPostService->checkHasSettingPolicyData();
        if (!$hasSettingPolicyData) {
            return view('admin.product.none_policy');
        }
        // Session::forget($this->keyProduct);
        $data = [];
        if (Session::has($this->keyProduct)) {
            $data = $this->productPostService->formatDataPageProduct(Session::get($this->keyProduct)[0]);
        }
        $conditionIdList = $this->product->getConditionIdList();
        $originType = $this->product->getOriginType();
        $data = $this->productPostService->getDataForShowPagePostProduct($data);
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
                $messageError = $postProductValidate->errors()->messages();
                $response['message_error'] = $this->productPostService->formatMessageError($messageError);
                return response()->json($response);
            }
            $dataSession = [];
            if (Session::has($this->keyProduct)) {
                $dataSession = Session::get($this->keyProduct)[0];
                Session::forget($this->keyProduct);
            }
            $data = $this->productPostService->formatDataInsertProductConfirm($data, $dataSession);
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
        $data = $this->productPostService->formatDataPageConfirm($data);
        return view('admin.product.confirm', compact('data'));
    }

    /**
     * post product publish
     * @return Illuminate\Http\Response
     */
    public function postProductPublish()
    {
        try {
            return $this->productPostService->postProductPublish();
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
            return $this->productPostService->apiGetItemEbayInfo($request->item_id);
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
            return $this->productPostService->apiGetItemYahooOrAmazonInfo($request->all());
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
                $messageError = $calculateProfitValidate->errors()->messages();
                $response['message_error'] = $this->productPostService->formatMessageError($messageError);
                return response()->json($response);
            }

            return $this->productPostService->calculatorProfit($data);
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
            return $this->productPostService->getImageInit($data);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * search category
     * @param  Request $request
     * @return json
     */
    public function searchCategory(Request $request)
    {
        $data = $request->all();
        $data = $this->category->search($data);
        return response()->json($data);
    }

    /**
     * list product
     * @param  Request $request
     * @return view
     */
    public function list(Request $request)
    {
        $exchangeRate    = $this->exchangeRate->getExchangeRateLatest();
        $products        = $this->product->getListProduct($request->all());
        $pathStorageFile = $this->itemImage->getPathStorageFile();
        $originType      = $this->product->getOriginType();
        // $category = $this->category->getAll();
        return view('admin.product.list', compact('products', 'pathStorageFile', 'originType', 'exchangeRate'));
    }

    /**
     * export csv
     * @return file
     */
    public function exportCsv()
    {
        return $this->productListService->exportCsv();
    }
}
