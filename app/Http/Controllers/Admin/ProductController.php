<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\ProductPostService;
use App\Services\ProductListService;
use App\Services\ProductEditService;
use App\Services\SaveSoldItemService;
use App\Services\ByFromYahooAuctionService;
use App\Models\Item;
use App\Models\CategoryFee;
use App\Models\MtbExchangeRate;
use App\Models\Authorization;
use App\Http\Requests\CalculateProfitRequest;
use App\Http\Requests\ItemSettingRequest;
use App\Http\Requests\PostProductRequest;
use Illuminate\Support\Facades\Session;
use App\Models\ItemImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;

class ProductController extends AbstractController
{
    protected $product;
    protected $category;
    protected $productPostService;
    protected $keyProduct;
    protected $keyProductEdit;
    protected $itemImage;
    protected $productListService;
    protected $exchangeRate;
    protected $productEditService;
    protected $user;
    protected $authorization;

    public function __construct(
        ProductPostService $productPostService,
        Item $product,
        CategoryFee $category,
        ProductListService $productListService,
        ProductEditService $productEditService,
        ItemImage $itemImage,
        User $user,
        Authorization $authorization,
        MtbExchangeRate $exchangeRate,
        SaveSoldItemService $saveSoldItemService,
        ByFromYahooAuctionService $byFromYahooAuctionService
    ) {
        $this->productPostService = $productPostService;
        $this->product            = $product;
        $this->category           = $category;
        $this->keyProduct         = Item::SESSION_KEY_PRODUCT_INFO;
        $this->keyProductEdit     = Item::SESSION_KEY_PRODUCT_EDIT_INFO;
        $this->itemImage          = $itemImage;
        $this->productListService = $productListService;
        $this->exchangeRate       = $exchangeRate;
        $this->productEditService = $productEditService;
        $this->user               = $user;
        $this->authorization      = $authorization;

        $this->saveSoldItemService = $saveSoldItemService;
        $this->byFromYahooAuctionService = $byFromYahooAuctionService;
    }

    /**
     * show page post product
     * @return view
     */
    public function showPagePostProduct()
    {
        // dd($this->byFromYahooAuctionService->byFromYahooAuction());
        // dd($this->saveSoldItemService->saveSoldItem());
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
        $originType      = $this->product->getOriginType();
        $data            = $this->productPostService->getDataForShowPagePostProduct($data);
        $userId          = Auth::user()->id;
        $isGuestAdmin    = Auth::user()->type == $this->user->getTypeGuestAdmin();
        $authorzation    = $this->authorization->findByUserId($userId);
        return view('admin.product.post', compact('data', 'originType', 'conditionIdList', 'authorzation', 'isGuestAdmin'));
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
            if (empty($data['dtb_item']['id'])
                && Auth::user()->type == $this->user->getTypeGuestAdmin()
            ) {
                $resultCheck = $this->productPostService->checkRegistLimit();
                if (!$resultCheck['status']) {
                    $response['message_error']['regis_limit'] = $resultCheck['messages'];
                    return response()->json($response);
                }
            }
            $dataSession = [];
            $keySession  = $this->keyProduct;
            $urlNext     = route('admin.product.show-confirm');
            if (!empty($data['dtb_item']['id'])) {
                $keySession = $this->keyProductEdit . '_' . $data['dtb_item']['id'];
                $urlNext    = route('admin.product.show-edit-confirm', ['itemId' => $data['dtb_item']['id']]);
            }
            if (Session::has($keySession)) {
                $dataSession = Session::get($keySession)[0];
                Session::forget($keySession);
            }
            $data = $this->productPostService->formatDataInsertProductConfirm($data, $dataSession);
            Session::push($keySession, $data);
            $response['status'] = true;
            $response['url']    = $urlNext;
            return response()->json($response);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * show page confirm
     * @return Illuminate\Support\Facades\View
     */
    public function showConfirm()
    {
        $data = Session::get($this->keyProduct)[0];
        if (!$data) {
            return redirect()->route('admin.product.show-page-post-product');
        }
        $data = $this->productPostService->formatDataPageConfirm($data);
        return view('admin.product.confirm', compact('data'));
    }

    /**
     * show page edit confirm
     * @return Illuminate\Support\Facades\View
     */
    public function showEditConfirm($itemId)
    {
        $keySession = $this->keyProductEdit . '_' . $itemId;
        $data = Session::get($keySession)[0];
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
    public function postProductPublish(Request $request)
    {
        try {
            return $this->productPostService->postProductPublish($request->all());
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
    public function getImageInit($itemId = null)
    {
        try {
            if ($itemId) {
                return $this->productEditService->getImageInit($itemId);
            } else {
                $data = Session::get($this->keyProduct)[0];
                return $this->productPostService->getImageInit($data);
            }
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
        $userId          = Auth::user()->id;
        $exchangeRate    = $this->exchangeRate->getExchangeRateLatest();
        $products        = $this->product->getListProduct($request->all(), $userId);
        $pathStorageFile = $this->itemImage->getPathStorageFile();
        $originType      = $this->product->getOriginType();
        $isMonitoring = $this->productListService->checkMonitoring();
        $monitoringType = $this->product->getPriceMonitoringSetting();
        return view('admin.product.list', compact('products', 'pathStorageFile', 'originType', 'exchangeRate', 'isMonitoring', 'monitoringType'));
    }

    /**
     * export csv
     * @return file
     */
    public function exportCsv()
    {
        $userId = Auth::user()->id;
        return $this->productListService->exportCsv($userId);
    }

    /**
     * update item
     * @param  Request $request
     * @return json
     */
    public function update(Request $request)
    {
        $response['status'] = false;
        try {
            $data = $request->all();
            $response['status'] = $this->productListService->updateItem($data);
            return response()->json($response);
        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json($response);
        }
    }

    /**
     * end item
     * @param  Request $request
     * @return json
     */
    public function endItem(Request $request)
    {
        $response['status'] = false;
        try {
            $data = $request->all();
            $response['status'] = $this->productListService->endItem($data);
            return response()->json($response);
        } catch (Exception $ex) {
            Log::error($ex);
            return response()->json($response);
        }
    }

    /**
     * show page post product
     * @return view
     */
    public function showPageEditProduct($itemId)
    {
        $item = $this->product->findById($itemId);
        if (!$item) {
            return view('not-found');
        }
        $data = [];
        $keySession = $this->keyProductEdit . '_' . $item['id'];
        if (Session::has($keySession)) {
            $dataSession = Session::get($keySession)[0];
            $data = $this->productEditService->formatDataPageProduct($dataSession);
        } else {
            $data = $this->productEditService->getDataForShowPageEditProduct($item);
        }
        $conditionIdList = $this->product->getConditionIdList();
        $originType      = $this->product->getOriginType();
        $userId          = Auth::user()->id;
        $isGuestAdmin    = Auth::user()->type == 2;
        $authorzation    = $this->authorization->findByUserId($userId);
        return view('admin.product.post', compact('data', 'originType', 'conditionIdList', 'authorzation', 'isGuestAdmin'));
    }

    /**
     * get setting template
     * @param  Request $request
     * @return Illuminate\Http\Response
     */
    public function getSettingTemplate(Request $request)
    {
        try {
            return $this->productPostService->getSettingTemplate($request->setting_template_id);
        } catch (Exception $ex) {
            Log::error($ex);
            $response['status'] = false;
            return response()->json($response);
        }
    }

    /**
     * show page setting product
     * @param  integer $itemId
     * @return view
     */
    public function showPageSettingProduct($itemId)
    {
        $item = $this->product->findById($itemId);
        if (!$item) {
            return view('not-found');
        }
        $userId                 = Auth::user()->id;
        $isMonitoring           = $this->authorization->findByUserId($userId)->monitoring ? true : false;
        if (!$isMonitoring) {
            return redirect()->route('admin.product.show-page-product-list');
        }
        $priceMonitoringSetting = $this->product->getPriceMonitoringSetting();
        return view('admin.product.setting', compact('item', 'priceMonitoringSetting'));
    }

    /**
     * setting update
     * @param  ItemSettingRequest $request
     * @param  integer             $itemId
     * @return redirect
     */
    public function settingUpdate(ItemSettingRequest $request, $itemId)
    {
        try {
            $item = $this->product->findById($itemId);
            if (!$item) {
                return view('not-found');
            }
            $data = $request->all();
            $this->product->updateItem($itemId, $data);
            return redirect()->back()
                ->with('message', Lang::get('message.update_setting_success'));
        } catch (Exception $ex) {
            return redirect()->back()
                ->with('error', Lang::get('message.update_setting_error'));
        }
    }
}
