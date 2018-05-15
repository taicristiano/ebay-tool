<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingFee extends AbstractModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_shipping_fee';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_id',
        'weight',
        'ship_fee',
    ];
    
    /**
     * define default data
     */
    const DEFAULT_WEIGHT   = 100;
    const DEFAULT_SHIP_FEE = 550;

    /**
     * get data master
     * @param  integer  $settingShippingId
     * @param  boolean $isEMS
     * @return array
     */
    public function getDataMaster($settingShippingId, $isEMS = false)
    {
        $now = date('Y-m-d H:i:s');
        if ($isEMS) {
            return [
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 500,
                    'ship_fee'    => 2000,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 600,
                    'ship_fee'    => 2180,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 700,
                    'ship_fee'    => 2360,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 800,
                    'ship_fee'    => 2540,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 900,
                    'ship_fee'    => 2720,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1000,
                    'ship_fee'    => 2900,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1250,
                    'ship_fee'    => 3300,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1250,
                    'ship_fee'    => 3300,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1500,
                    'ship_fee'    => 3700,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1750,
                    'ship_fee'    => 4100,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 2000,
                    'ship_fee'    => 4500,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 2500,
                    'ship_fee'    => 5200,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 3000,
                    'ship_fee'    => 5900,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 3500,
                    'ship_fee'    => 6600,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 4000,
                    'ship_fee'    => 7300,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 4500,
                    'ship_fee'    => 8000,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 5000,
                    'ship_fee'    => 8700,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 5500,
                    'ship_fee'    => 9400,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 6000,
                    'ship_fee'    => 10100,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ]
            ];
        }
        return [
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 100,
                'ship_fee'    => 550,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 200,
                'ship_fee'    => 620,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 300,
                'ship_fee'    => 690,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 400,
                'ship_fee'    => 780,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 500,
                'ship_fee'    => 870,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 600,
                'ship_fee'    => 960,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 700,
                'ship_fee'    => 1050,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 800,
                'ship_fee'    => 1140,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 900,
                'ship_fee'    => 1230,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1000,
                'ship_fee'    => 1320,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1250,
                'ship_fee'    => 1500,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1500,
                'ship_fee'    => 1860,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1750,
                'ship_fee'    => 2130,
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 2000,
                'ship_fee'    => 2400,
                'created_at'  => $now,
                'updated_at'  => $now
            ]
        ];
    }

    /**
     * create default data
     * @param  integer $shippingId
     * @return boolean
     */
    public function createDefaultData($shippingId)
    {
        $currentTimestamp = date('Y-m-d H:i:s');
        return $this->insert([
            'shipping_id' => $shippingId,
            'weight'      => static::DEFAULT_WEIGHT,
            'ship_fee'    => static::DEFAULT_SHIP_FEE,
            'created_at'  => $currentTimestamp,
            'updated_at'  => $currentTimestamp,
        ]);
    }

    /**
     * get shipping fee by shipping
     * @param  array|integer $shippingId
     * @return Collections
     */
    public function getFeeListByShipping($shippingId)
    {
        return $this->select('id', 'shipping_id', 'weight', 'ship_fee')->where('shipping_id', $shippingId)->paginate();
    }
}
