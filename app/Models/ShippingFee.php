<?php

namespace App\Models;

class ShippingFee extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_shipping_fee';

    /**
     * get data master
     * @param  integer  $settingShippingId
     * @param  boolean $isEMS
     * @return array
     */
    public function getDataMaster($settingShippingId, $isEMS = false)
    {
        if ($isEMS) {
            return [
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 500,
                    'ship_fee'    => 2000
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 600,
                    'ship_fee'    => 2180
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 700,
                    'ship_fee'    => 2360
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 800,
                    'ship_fee'    => 2540
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 900,
                    'ship_fee'    => 2720
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1000,
                    'ship_fee'    => 2900
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1250,
                    'ship_fee'    => 3300
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1250,
                    'ship_fee'    => 3300
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1500,
                    'ship_fee'    => 3700
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 1750,
                    'ship_fee'    => 4100
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 2000,
                    'ship_fee'    => 4500
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 2500,
                    'ship_fee'    => 5200
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 3000,
                    'ship_fee'    => 5900
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 3500,
                    'ship_fee'    => 6600
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 4000,
                    'ship_fee'    => 7300
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 4500,
                    'ship_fee'    => 8000
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 5000,
                    'ship_fee'    => 8700
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 5500,
                    'ship_fee'    => 9400
                ],
                [
                    'shipping_id' => $settingShippingId,
                    'weight'      => 6000,
                    'ship_fee'    => 10100
                ]
            ];
        }
        return [
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 100,
                'ship_fee'    => 550
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 200,
                'ship_fee'    => 620,
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 300,
                'ship_fee'    => 690
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 400,
                'ship_fee'    => 780
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 500,
                'ship_fee'    => 870
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 600,
                'ship_fee'    => 960
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 700,
                'ship_fee'    => 1050
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 800,
                'ship_fee'    => 1140
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 900,
                'ship_fee'    => 1230
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1000,
                'ship_fee'    => 1320
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1250,
                'ship_fee'    => 1500
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1500,
                'ship_fee'    => 1860
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 1750,
                'ship_fee'    => 2130
            ],
            [
                'shipping_id' => $settingShippingId,
                'weight'      => 2000,
                'ship_fee'    => 2400
            ]
        ];
    }
}
