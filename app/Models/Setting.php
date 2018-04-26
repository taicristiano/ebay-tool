<?php

namespace App\Models;

class Setting extends AbstractModel
{
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'paypal_fee_rate',
        'paypal_fixed_fee',
        'ex_rate_diff',
        'gift_discount',
        'duration',
        'quantity',
    ];

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
}
