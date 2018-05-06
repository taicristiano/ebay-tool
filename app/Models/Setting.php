<?php

namespace App\Models;

class Setting extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_setting';

    /**
     * define default field value
     */
    const DEFAULT_STORE            = 1;
    const DEFAULT_PAYPAL_FEE_RATE  = 3.6;
    const DEFAULT_PAYPAL_FIXED_FEE = 40;
    const DEFAULT_EX_RATE_DIFF     = 0;
    const DEFAULT_GIFT_DISCOUNT    = 100;
    const DEFAULT_DURATION         = 30;
    const DEFAULT_QUANTITY         = 1;

    protected $guarded = [];

    /**
     * update or create by user_id
     * @param  integer $userId
     * @param  array  $data
     * @return mixed
     */
    public function updateOrCreateByUserId($userId, $data = [])
    {
        if (empty($data)) {
            $data = [
                'store_id'         => static::DEFAULT_STORE,
                'paypal_fee_rate'  => static::DEFAULT_PAYPAL_FEE_RATE,
                'paypal_fixed_fee' => static::DEFAULT_PAYPAL_FIXED_FEE,
                'ex_rate_diff'     => static::DEFAULT_EX_RATE_DIFF,
                'gift_discount'    => static::DEFAULT_GIFT_DISCOUNT,
                'duration'         => static::DEFAULT_DURATION,
                'quantity'         => static::DEFAULT_QUANTITY,
            ];
        }
        return $this->updateOrCreate(['user_id' => $userId], $data);
    }

    /**
     * get setting of user
     * @param  integer $userId
     * @return object
     */
    public function getSettingOfUser($userId)
    {
        return $this->where('user_id', $userId)
            ->first();
    }

    /**
     * update setting
     * @param  integer $id
     * @param  array $data
     * @return boolean
     */
    public function updateSetting($id, $data)
    {
        return $this->where('id', $id)
            ->update($data);
    }
}
