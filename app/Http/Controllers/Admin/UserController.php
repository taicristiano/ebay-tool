<?php

namespace App\Http\Controllers\Admin;

use App\Models\Authorization;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use App\Services\CsvService;
use App\Http\Requests\UploadCsvRequest;
use Exception;
use Lang;

class UserController extends AbstractController
{
    protected $user;
    protected $authorization;
    protected $csvService;

    public function __construct(User $user, Authorization $authorization, CsvService $csvService)
    {
        $this->user          = $user;
        $this->authorization = $authorization;
        $this->csvService    = $csvService;
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
     * @param  Request $req
     * @param  integer|null  $userId
     * @return view|redirect
     */
    public function create(Request $req, $userId = null)
    {
        $typeOptions     = $this->user->getTypeOptions();
        $categoryOptions = $this->authorization->getCategoryOptions();
        if ($req->isMethod('GET')) {
            $typeGuestAdmin = User::TYPE_GUEST_ADMIN;
            if ($userId) {
                $user = $this->user->findOrFail($userId);
            }
            return $this->render(compact('user', 'typeOptions', 'categoryOptions', 'typeGuestAdmin'));
        }
        $req->validate([
            'user_name'  => 'required|max:255|regex:/^\w+$/|unique:' . $this->user->getTable() . ($userId ? ',id,' . $userId : ''),
            'type'       => 'required|in:' . implode(',', array_keys($typeOptions)),
            'password'   => $userId ? 'nullable' : 'required' . '|confirmed|digits_between:6,10',
            'category.*' => 'in:' . implode(',', array_keys($categoryOptions)),
        ]);
        $data = array_filter($req->only($this->user->getFieldList()));
        try {
            DB::beginTransaction();
            if ($user = $this->user->updateOrCreate(['id' => $userId], $data)) {
                $this->authorization->createByUserId($user->id, $req->category);
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
}
