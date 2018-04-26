<?php

namespace App\Models;

class Shipping extends AbstractModel
{
    protected $table = 'dtb_setting_shipping';

    const SHIPPING_NAME         = 'eパケットライト';
    const SHIPPING_NAME_EMS     = 'EMS';
    const DEFAULT_MAX_SIZE      = 90;
    const DEFAULT_SIDE_MAX_SIZE = 60;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'shipping_name',
        'max_size',
        'side_max_size',
    ];

    /**
     * create default shipping
     * @param  integer $userId
     * @return mixed
     */
    public function createDefaultShipping($userId)
    {
        foreach (config('shipping_fee') as $shippingName => $shippingFees) {
            if ($shippingName == static::SHIPPING_NAME_EMS) {
                $shippingData = [
                    'shipping_name' => static::SHIPPING_NAME_EMS,
                ];
            } else {
                $shippingData = [
                    'shipping_name' => static::SHIPPING_NAME,
                    'max_size'      => static::DEFAULT_MAX_SIZE,
                    'side_max_size' => static::DEFAULT_SIDE_MAX_SIZE,
                ];
            }
            $shippingData['user_id'] = $userId;
            $shipping = new static;
            $shipping->fill($shippingData)->save();
            $shippingFeeData = [];
            foreach ($shippingFees as $fee) {
                $nowTimestamp      = date('Y-m-d H:i:s');
                $shippingFeeData[] = [
                    'shipping_id' => $shipping->id,
                    'weight'      => $fee['weight'],
                    'ship_fee'    => $fee['ship_fee'],
                    'created_at'  => $nowTimestamp,
                    'updated_at'  => $nowTimestamp,
                ];
            }
            ShippingFee::insert($shippingFeeData);
        }
    }
}
