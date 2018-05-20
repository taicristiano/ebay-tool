<?php

namespace App\Http\Controllers\Admin;
use Log;
use Goutte\Client;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Item;
use App\Http\Requests\UpdateProfitRequest;

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
        $originType = $this->product->getOriginType();
        return view('admin.product.post', compact('data', 'originType'));
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
    }

    public function postProduct(Request $request)
    {
        // http://localhost/ebayTool/public/storage/test/rtRt2_1526574828.png
        $data = $request->all();
        dd($this->productService->postProduct($data));
        // $this->productService->uploadFile($data['files_upload_4'], 'public/test');
    }

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

    public function postProductConfirm($id = null)
    {
        return view('admin.product.confirm');
    }

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
}

