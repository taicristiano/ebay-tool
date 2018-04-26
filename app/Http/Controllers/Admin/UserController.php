<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\Authorization;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\ShippingFee;
use App\Models\User;
use DB;
use Exception;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends AbstractController
{
    public function __construct(User $user, Authorization $authorization, Setting $setting, Shipping $shipping, ShippingFee $shippingFee)
    {
        $this->user          = $user;
        $this->authorization = $authorization;
        $this->setting       = $setting;
        $this->shipping      = $shipping;
        $this->shippingFee   = $shippingFee;
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
                'message' => __('message.' . ($userId ? 'update' : 'create') . '_user_success'),
            ]);
        } catch (Exception | QueryException $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors([
                'message' => __('message.server_error'),
            ]);
        }
    }

    /**
     * upload user from csv
     * @return view|redirect
     */
    public function upload()
    {
        return $this->render();
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
     * fetch user
     * @param  Request $req
     * @return JSON
     */
    public function fetch(Request $req)
    {
        $results = $this->user->fetch($req);
        return response()->json($this->user->fetch($req));
    }
}
