<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\User;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use App\Services\SettingService;
use App\Http\Requests\NormalSettingRequest;
use Lang;
use App\Models\MtbStore;
use App\Models\SettingPolicy;
use App\Models\Item;
use Auth;

class SettingNormalController extends AbstractController
{
    protected $user;
    protected $setting;
    protected $store;
    protected $settingPolicy;
    protected $settingService;
    protected $product;

    public function __construct(
        User $user,
        Setting $setting,
        MtbStore $store,
        SettingService $settingService,
        SettingPolicy $settingPolicy,
        Item $product
    )
    {
        $this->user           = $user;
        $this->setting        = $setting;
        $this->store          = $store;
        $this->settingService = $settingService;
        $this->settingPolicy  = $settingPolicy;
        $this->product        = $product;
    }

    /**
     * show view normal setting
     * @return view
     */
    public function normalSetting(Request $request)
    {
        $userId = Auth::user()->id;
        if ($request->has('username')) {
            $result = $this->settingService->apiFetchToken();
            $this->user->updateById($userId, $result);
            return redirect()->route('admin.user.normal_setting')->with('message', Lang::get('message.get_token_success'));
        }
        $isShowButtonGetToken = $this->settingService->checkDisplayButtonGetToken();
        $setting = $this->setting->getSettingOfUser($userId);
        $stores = $this->store->getAllStore();
        $storeOption = $this->settingService->getOptionStores($stores);
        $durationOption = $this->product->getDurationOption();
        return view('admin.setting.normal', compact('storeOption', 'setting', 'isShowButtonGetToken', 'durationOption'));
    }

    /**
     * normal setting update
     * @param  NormalSettingRequest $request
     * @return redirect
     */
    public function normalSettingUpdate(NormalSettingRequest $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $dataSetting = $this->settingService->formatDataSetting($data);
            $this->setting->updateSetting($id, $dataSetting);
            return redirect()->back()
                ->with('message', Lang::get('message.update_setting_success'));
        } catch (Exception $ex) {
            return redirect()->back()
                ->with('error', Lang::get('message.update_setting_error'));
        }
    }

    /**
     * api get session id
     * @return redirect
     */
    public function apiGetSessionId()
    {
        try {
            $sessionId = $this->settingService->apiGetSessionId();
            return redirect(config('api_info.url_redirect_get_session_id') . $sessionId);
        } catch (Exception $ex) {
            return redirect()->back()
                ->with('error', Lang::get('message.get_session_error'));
        }
    }

    /**
     * api get policy
     * @return redirect
     */
    public function apiGetPolicy()
    {
        try {
            DB::beginTransaction();
            $userId = Auth::user()->id;
            $dataPolicy = $this->settingService->apiGetPolicy();
            foreach ($dataPolicy as &$item) {
                $item['user_id'] = $userId;
                $item['policy_type'] = $this->settingPolicy->getTypeByStringName($item['policy_type']);
                $item['created_at'] = date('Y-m-d H:i:s');
                $item['updated_at'] = date('Y-m-d H:i:s');
            }
            $this->settingPolicy->deleteByUserId($userId);
            $this->settingPolicy->insert($dataPolicy);
            DB::commit();
            return redirect()->route('admin.user.normal_setting')->with('message', Lang::get('message.get_policy_success'));
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.user.normal_setting')->with('error', Lang::get('message.get_policy_error'));
        }
    }
}
