<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SettingShipping extends AbstractModel
{
    use SoftDeletes;
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
     * @return void
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
            $shipping                = new static;
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
                'updated_at'    => $now,
            ],
            [
                'user_id'       => $userId,
                'shipping_name' => static::SHIPPING_NAME_EMS,
                'max_size'      => 0,
                'side_max_size' => 0,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];
    }

    /**
     * get shipping list
     * @param  integer $userId
     * @param  boolean $withUser
     * @return Collections
     */
    public function getShippingList($userId = null, $withUser = false)
    {
        $whereRaw = "
                {$this->getTable()}.id,
                {$this->getTable()}.user_id,
                {$this->getTable()}.shipping_name,
                {$this->getTable()}.max_size,
                {$this->getTable()}.side_max_size";
        $shippings = $this
            ->whereRaw($userId ? "({$this->getTable()}.user_id = $userId)" : '1');
        if ($withUser) {
            $userTable = (new User)->getTable();
            $whereRaw .= ",{$userTable}.user_name";
            $shippings = $shippings
                ->join($userTable, "{$this->getTable()}.user_id", "{$userTable}.id")
                ->selectRaw($whereRaw)
                ->orderBy("{$userTable}.id", 'desc')
                ->orderBy("{$this->getTable()}.id", 'desc');
        }
        return $shippings->paginate();
    }

    /*
     * get setting shipping of user
     * @param  integer $userId
     * @return array object
     */
    public function getSettingShippingOfUser($userId)
    {
        return $this->where('user_id', $userId)
            ->get();
    }

    /**
     * find setting shipping max size of user
     * @param  integer $userId
     * @return array object
     */
    public function findSettingShippingMaxSizeOfUser($userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('side_max_size', 'desc')
            ->orderBy('max_size', 'desc')
            ->first();
    }
}
