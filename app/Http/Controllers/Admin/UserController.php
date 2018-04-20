<?php

namespace App\Http\Controllers\Admin;

use App\Models\Authorization;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class UserController extends AbstractController
{
    public function __construct(User $user, Authorization $authorization)
    {
        $this->User          = $user;
        $this->Authorization = $authorization;
    }

    /**
     * user index action
     * @param  Request $req
     * @return view
     */
    public function index(Request $req)
    {
        $filters     = array_filter($req->only($this->User->getFilter()));
        $typeOptions = $this->User->getTypeOptions(['' => __('view.display_all')]);
        $users       = $this->User->getList($filters);
        return $this->render(compact('users', 'typeOptions', 'filters'));
    }

    /**
     * user create action
     * @param  Request $req
     * @param  integer|null  $userId
     * @return view|redirect
     */
    public function create(Request $req, $userId = null)
    {
        $typeOptions     = $this->User->getTypeOptions();
        $categoryOptions = $this->Authorization->getCategoryOptions();
        if ($req->isMethod('GET')) {
            $typeGuestAdmin = User::TYPE_GUEST_ADMIN;
            if ($userId) {
                $user = $this->User->findOrFail($userId);
            }
            return $this->render(compact('user', 'typeOptions', 'categoryOptions', 'typeGuestAdmin'));
        }
        $req->validate([
            'user_name'  => 'required|max:255|regex:/^\w+$/|unique:' . $this->User->getTable() . ($userId ? ',id,' . $userId : ''),
            'type'       => 'required|in:' . implode(',', array_keys($typeOptions)),
            'password'   => $userId ? 'nullable' : 'required' . '|confirmed|digits_between:6,10',
            'category.*' => 'in:' . implode(',', array_keys($categoryOptions)),
        ]);
        $data = array_filter($req->only($this->User->getFieldList()));
        try {
            DB::beginTransaction();
            if ($user = $this->User->updateOrCreate(['id' => $userId], $data)) {
                $this->Authorization->createByUserId($user->id, $req->category);
            }
            DB::commit();
            return redirect()->back()->with([
                'message' => __('message.' . ($userId ? 'update' : 'create') . '_user_success'),
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([
                'message' => __('message.server_error'),
            ]);
        }
    }

    /**
     * delete user action
     * @param  integer $userId
     * @return redirect
     */
    public function delete($userId)
    {
        $this->User->findOrFail($userId)->delete();
        return redirect()->back()->with(['message' => __('message.deleted_user_success')]);
    }
}
