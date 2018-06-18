<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SettingTemplate extends AbstractModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_setting_template';

    /**
     * field list
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    /**
     * get template list
     * @param  integer $userId
     * @return Collections
     */
    public function getTemplateList($userId)
    {
        return $this->select(['id', 'title', 'created_at', 'updated_at'])->where('user_id', $userId)->paginate();
    }

    /**
     * get by user id
     * @param  integer $userId
     * @return array
     */
    public function getByUserId($userId)
    {
        return $this->select('id', 'title', 'content')
            ->whereUserId($userId)
            ->get()
            ->toArray();
    }
}
