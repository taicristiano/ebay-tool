<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * define user type
     */
    const TYPE_NORMAL_USER      = 1;
    const TYPE_SUPER_ADMIN      = 2;
    const TYPE_GUEST_ADMIN      = 3;
    const TYPE_CANCELATION_USER = 4;
    const DEFAULT_PASSWORD      = '12345678';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_user';

    /**
     * field list
     * @var array
     */
    protected $fillable = [
        'type',
        'user_code',
        'user_name',
        'name_kana',
        'introducer_id',
        'ebay_account',
        'start_date',
        'tel',
        'email',
        'password',
        'memo',
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * filter able
     * @var array
     */
    protected $filter = [
        'type',
        'search',
    ];

    /**
     * get filter
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * get field list
     * @return array
     */
    public function getFieldList()
    {
        return $this->fillable;
    }

    /**
     * check is super admin type
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->type == static::TYPE_SUPER_ADMIN;
    }

    /**
     * check is guest admin type
     * @return boolean
     */
    public function isGuestAdmin()
    {
        return $this->type == static::TYPE_GUEST_ADMIN;
    }

    /**
     * get guest admin type
     * @return integer
     */
    public function getTypeGuestAdmin()
    {
        return static::TYPE_GUEST_ADMIN;
    }

    /**
     * check type setting by type
     * @param  integer  $type
     * @return boolean
     */
    public function isSetting($type)
    {
        return in_array($type, [static::TYPE_SUPER_ADMIN, static::TYPE_GUEST_ADMIN]);
    }

    /**
     * check is user type
     * @return boolean
     */
    public function isNormalUser()
    {
        return $this->type == static::TYPE_NORMAL_USER;
    }

    /**
     * check is user cancelation type
     * @return boolean
     */
    public function isCancelationUser()
    {
        return $this->type == static::TYPE_CANCELATION_USER;
    }

    /**
     * render type as string
     * @return string
     */
    public function renderTypeAsString()
    {
        return $this->getTypeOptions()[$this->type];
    }

    /**
     * get role option
     * @return array
     */
    public function getTypeOptions($input = [])
    {
        $input[static::TYPE_SUPER_ADMIN]      = __('user_type.type_super_admin');
        $input[static::TYPE_GUEST_ADMIN]      = __('user_type.type_guest_admin');
        $input[static::TYPE_NORMAL_USER]      = __('user_type.type_normal_user');
        $input[static::TYPE_CANCELATION_USER] = __('user_type.type_cancelation_user');
        return $input;
    }

    /**
     * get authorizations
     * @return array
     */
    public function getAuthorizationAttribute()
    {
        $result = [];
        if ($authorizations = $this
            ->hasMany('App\Models\Authorization')
            ->selectRaw('yahoo_info, amazon_info, monitoring')
            ->first()) {
            $authorizations->yahoo_info ? $result[]  = Authorization::YAHOO_AUCTION_INFO : '';
            $authorizations->amazon_info ? $result[] = Authorization::AMAZONE_INFO : '';
            $authorizations->monitoring ? $result[]  = Authorization::MONITORING_PRODUCT : '';
        }
        return $result;
    }

    /**
     * get by id with authorization
     * @param  integer $userId
     * @return Collection
     */
    public function getByIdWithAuthorization($userId)
    {
        $authorizationTable = (new Authorization)->getTable();
        $userTable          = $this->getTable();
        if ($user = $this
            ->selectRaw("
                $userTable.*,
                $authorizationTable.regist_limit,
                $authorizationTable.post_limit
            ")
            ->leftJoin($authorizationTable, "$userTable.id", "$authorizationTable.user_id")
            ->where("$userTable.id", $userId)
            ->first()) {
            return $user;
        }
        abort(404);
    }

    /**
     * get list user
     * @return Collections
     */
    public function getList($filter = [])
    {
        $result = $this->selectRaw('
            id,
            user_code,
            user_name,
            name_kana,
            email,
            tel,
            type,
            memo
        ');
        if (isset($filter['type'])) {
            $result = $result->where('type', $filter['type']);
        }
        if (isset($filter['search'])) {
            $result = $result
                ->where('user_name', 'LIKE', '%' . $filter['search'] . '%')
                ->orWhere('user_code', 'LIKE', '%' . $filter['search'] . '%');
        }
        return $result->paginate()->appends($filter);
    }

    /**
     * get data exprot csv
     * @param  array $data
     * @return array object
     */
    public function getDataExportCsv($data)
    {
        if ($data['type_csv'] == 'full') {
            $fieldSelect = [
                'start_date',
                'user_name',
                'user_code',
                'name_kana',
                'email',
                'tel',
                'ebay_account',
                'type',
                'introducer_id',
                'memo',
                'dtb_authorization.yahoo_info',
                'dtb_authorization.amazon_info',
                'dtb_authorization.monitoring',
                'dtb_authorization.regist_limit',
                'dtb_authorization.post_limit',
            ];
            $condition = $this->select($fieldSelect)
                ->leftJoin('dtb_authorization', 'dtb_user.id', '=', 'dtb_authorization.user_id');
        } else {
            $fieldSelect = [
                'tel',
                'user_code',
                'email',
                'memo',
            ];
            $condition = $this->select($fieldSelect);
        }
        if (!empty($data['type_user'])) {
            $condition = $condition->where('type', $data['type_user']);
        }
        if (!empty($data['user_name'])) {
            $condition = $condition->where('user_name', 'LIKE', '%' . $filter['user_name'] . '%');
        }
        return $condition->get()->toArray();
    }

    /**
     * find user by email
     * @param  string $email
     * @return object
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)
            ->first();
    }

    /**
     * fetch user
     * @param  Object $req
     * @return Collections
     */
    public function fetch($req)
    {
        $result = $this
            ->select('id', 'user_name as text')
            ->where('id', 'LIKE', "%$req->search%")
            ->orWhere('user_name', 'LIKE', '%' . $req->search . '%')
            ->orWhere('name_kana', 'LIKE', '%' . $req->search . '%')
            ->skip(($req->page - 1) * $req->limit)
            ->limit($req->limit);
        return [
            'results'        => $result->get()->toArray(),
            'count_filtered' => $result->count(),
        ];
    }

    /**
     * get introducer option
     * @param  integer $userId
     * @return array
     */
    public static function getIntroducerOption($userId)
    {
        return static::select('id', 'user_name')->where('id', $userId)->get()->pluck('user_name', 'id')->toArray();
    }

    /**
     * set user_code
     */
    public static function generateUserCode()
    {
        $yearMonth = date('ym');
        if ($lastUser = static::select('user_code')->whereNotNull('user_code')->orderBy('id', 'desc')->first()) {
            $lastIndex = ((Int) preg_replace('/^[0-9]{4}/', '', $lastUser->user_code) + 1);
            return $yearMonth . str_repeat('0', (3 - strlen($lastIndex))) . $lastIndex;
        }
        return $yearMonth . '001';
    }

    /**
     * update by id
     * @param  integer $id
     * @param  array $data
     * @return boolean
     */
    public function updateById($id, $data)
    {
        return $this->where('id', $id)
            ->update($data);
    }
}
