<?php

namespace App\Models;

class Authorization extends AbstractModel
{
    /**
     * define category
     */
    const YAHOO_AUCTION_INFO = 1;
    const AMAZONE_INFO       = 2;
    const MONITORING_PRODUCT = 3;

    protected $table = 'dtb_authorization';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category',
    ];

    /**
     * get category
     * @return array
     */
    public function getCategoryOptions($input = [])
    {
        $input[static::YAHOO_AUCTION_INFO] = __('authorization.yahoo_auction_info');
        $input[static::AMAZONE_INFO]       = __('authorization.amazone_info');
        $input[static::MONITORING_PRODUCT] = __('authorization.monitoring_product');
        return $input;
    }

    /**
     * create authorization by user_id
     * @param  integer $userId
     * @param  array  $category
     * @return mixed
     */
    public function createByUserId($userId, $category = [])
    {
        $data = [];
        $this->where('user_id', $userId)->delete();
        foreach ($category as $item) {
            $data[] = [
                'user_id'    => $userId,
                'category'   => $item,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        return $this->insert($data);
    }
}
