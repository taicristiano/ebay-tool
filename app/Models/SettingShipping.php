<?php

namespace App\Models;

class SettingShipping extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_setting_shipping';

    const SHIPPING_NAME         = 'パケットライト';
    const SHIPPING_NAME_EMS     = 'EMS';
    const DEFAULT_MAX_SIZE      = 90;
    const DEFAULT_SIDE_MAX_SIZE = 60;

    protected $guarded = [];

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

    /**
     * get data master
     * @param  integer $userId
     * @return array
     */
    public function getDataMaster($userId)
    {
        $now = date('Y-m-d H:i:s');
        return [
            [
                'user_id'       => $userId,
                'shipping_name' => static::SHIPPING_NAME,
                'max_size'      => static::DEFAULT_MAX_SIZE,
                'side_max_size' => static::DEFAULT_SIDE_MAX_SIZE,
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            [
                'user_id'       => $userId,
                'shipping_name' => static::SHIPPING_NAME_EMS,
                'max_size'      => 0,
                'side_max_size' => 0,
                'created_at'    => $now,
                'updated_at'    => $now
            ]
        ];
    }
}
