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

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'password', 'type',
    ];

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
        'user_name',
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
        return $this->hasMany('App\Models\Authorization')->get()->pluck('category')->toArray();
    }

    /**
     * get list user
     * @return Collections
     */
    public function getList($filter = [])
    {
        $result = $this->selectRaw('id, user_name, type');
        if (isset($filter['type'])) {
            $result = $result->where('type', $filter['type']);
        }
        if (isset($filter['user_name'])) {
            $result = $result->where('user_name', 'LIKE', '%' . $filter['user_name'] . '%');
        }
        return $result->paginate()->appends($filter);
    }
}
