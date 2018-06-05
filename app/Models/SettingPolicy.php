<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SettingPolicy extends AbstractModel
{
    use SoftDeletes;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_setting_policies';

    protected $guarded = [];

    const TYPE_SHIPPING = 1;
    const TYPE_PAYMENT  = 2;
    const TYPE_RETURN   = 3;

    const STRING_TYPE_SHIPPING = 'SHIPPING';
    const STRING_TYPE_PAYMENT  = 'PAYMENT';
    const STRING_TYPE_RETURN   = 'RETURN';

    /**
     * get type by string name
     * @param  string $stringName
     * @return integer
     */
    public function getTypeByStringName($stringName)
    {
        $arrayType = $this->getTypeOption();
        return array_search($stringName, $arrayType);
    }

    /**
     * get type option
     * @return array
     */
    public function getTypeOption()
    {
        return [
            self::TYPE_SHIPPING => self::STRING_TYPE_SHIPPING,
            self::TYPE_PAYMENT => self::STRING_TYPE_PAYMENT,
            self::TYPE_RETURN => self::STRING_TYPE_RETURN
        ];
    }

    /**
     * delete by user id
     * @param  integer $userId
     * @return boolean
     */
    public function deleteByUserId($userId)
    {
        return $this->where('user_id', $userId)->delete();
    }

    /**
     * get setting policy of user
     * @param  integer $userId
     * @return object
     */
    public function getSettingPolicyOfUser($userId)
    {
        return $this->select('id', 'policy_name', 'policy_type')
            ->where('user_id', $userId)
            ->get();
    }
}
