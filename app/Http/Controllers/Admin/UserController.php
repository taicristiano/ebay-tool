<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\Authorization;
use App\Models\Setting;
use App\Models\SettingShipping;
use App\Models\ShippingFee;
use App\Models\User;
use DB;
use Exception;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\CsvService;
use App\Services\SettingService;
use App\Http\Requests\UploadCsvRequest;
use App\Http\Requests\NormalSettingRequest ;
use Lang;
use App\Models\MtbStore;
use App\Models\SettingPolicy;
use Auth;

class UserController extends AbstractController
{
    protected $user;
    protected $authorization;
    protected $csvService;
    protected $setting;
    protected $shipping;
    protected $shippingFee;
    protected $store;
    protected $settingPolicy;

    public function __construct(
        User $user,
        Authorization $authorization,
        CsvService $csvService,
        Setting $setting,
        SettingShipping $shipping,
        ShippingFee $shippingFee,
        MtbStore $store,
        SettingService $settingService,
        SettingPolicy $settingPolicy
    )
    {
        $this->user           = $user;
        $this->authorization  = $authorization;
        $this->csvService     = $csvService;
        $this->setting        = $setting;
        $this->shipping       = $shipping;
        $this->shippingFee    = $shippingFee;
        $this->store          = $store;
        $this->settingService = $settingService;
        $this->settingPolicy  = $settingPolicy;
    }

    /**
     * user index action
     * @param  Request $req
     * @return view
     */
    public function index(Request $req)
    {
        $filters     = array_filter($req->only($this->user->getFilter()));
        $typeOptions = $this->user->getTypeOptions(['' => __('view.display_all')]);
        $users       = $this->user->getList($filters);
        return $this->render(compact('users', 'typeOptions', 'filters'));
    }

    /**
     * user create action
     * @param  UserRequest $req
     * @param  integer|null  $userId
     * @return view|redirect
     */
    public function create(UserRequest $req, $userId = null)
    {
        $typeOptions     = $this->user->getTypeOptions();
        $categoryOptions = $this->authorization->getCategoryOptions();
        if ($req->isMethod('GET')) {
            $typeGuestAdmin = User::TYPE_GUEST_ADMIN;
            if ($userId) {
                $user = $this->user->getByIdWithAuthorization($userId);
                if ($user->introducer_id) {
                    session()->flash('introducer', User::getIntroducerOption($user->introducer_id));
                }
            }
            return $this->render(compact('user', 'typeOptions', 'categoryOptions', 'typeGuestAdmin'));
        }
        $data             = array_filter($req->only($this->user->getFieldList()));
        $data['password'] = Hash::make(isset($data['password']) ? $data['password'] : User::DEFAULT_PASSWORD);
        if (!$userId) {
            $data['user_code'] = User::generateUserCode();
        }
        try {
            DB::beginTransaction();
            $user = $this->user->updateOrCreate(['id' => $userId], $data);
            if ($data['type'] == User::TYPE_GUEST_ADMIN) {
                $this->authorization->updateOrCreateCategoryByUserId($user->id, $req->only(['regist_limit', 'post_limit', 'category']));
            }
            if (!$userId) {
                $this->setting->updateOrCreateByUserId($user->id);
                $this->shipping->createDefaultShipping($user->id);
            }
            DB::commit();
            session()->forget('introducer');
            return redirect()->back()->with([
                'message' => __('message.' . ($userId ? 'updated' : 'created') . '_user_success'),
            ]);
        } catch (Exception | QueryException $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with(['error' => __('message.server_error')]);
        }
    }

    /**
     * delete user action
     * @param  integer $userId
     * @return redirect
     */
    public function delete($userId)
    {
        $this->user->findOrFail($userId)->delete();
        return redirect()->back()->with(['message' => __('message.deleted_user_success')]);
    }

    /**
     * export csv
     * @param  Request $request
     * @return file
     */
    public function exportCsv(Request $request)
    {
        $data = $request->all();
        $data = $this->csvService->formatDataExprotCsv($data);
        $listUser = $this->user->getDataExportCsv($data);
        return $this->csvService->exportCsv($data['type_csv'], $listUser);
    }

    /**
     * show page upload csv
     * @return view
     */
    public function showPageuploadCsv()
    {
        return view('admin.user.upload-csv');
    }

    /**
     * upload csv
     * @param  UploadCsvRequest $request
     * @return view
     */
    public function uploadCsv(UploadCsvRequest $request)
    {
        try {
            $file = $request->file('file_csv');
            $results = $this->csvService->uploadCsv($file);
            if ($results) {
                return redirect()->back()
                    ->with('message', Lang::get('message.upload_csv_success'));
            }
            return redirect()->back()
                ->with('error', Lang::get('message.error_while_upload'));
        } catch(Exception $exception) {
            return redirect()->back()
                ->with('error', Lang::get('message.error_while_upload'));
        }
    }

    /**
     * fetch user
     * @param  Request $req
     * @return JSON
     */
    public function fetch(Request $req)
    {
        $results = $this->user->fetch($req);
        return response()->json($this->user->fetch($req));
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
            $this->user->updateOrCreate(['id' => $userId], $result);
            return redirect()->route('admin.user.normal_setting')->with('message', Lang::get('message.get_token_success'));
        }
        $isShowButtonGetToken = $this->settingService->checkDisplayButtonGetToken();
        $setting = $this->setting->getSettingOfUser($userId);
        $stores = $this->store->getAllStore();
        $storeOption = $this->settingService->getOptionStores($stores);
        $durationOption =$this->setting->getDurationOption();
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
