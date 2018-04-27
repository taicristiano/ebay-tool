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

    public function getDataMaster($userId)
    {
        return [
            [
                'user_id'       => $userId,
                'shipping_name' => 'パケットライト',
                'max_size'      => 90,
                'side_max_size' => 60,
            ],
            [
                'user_id'       => $userId,
                'shipping_name' => 'EMS',
                'max_size'      => 0,
                'side_max_size' => 0,
            ]
        ];
    }
}
