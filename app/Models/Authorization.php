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

    const ALLOW = 1;
    const DENY  = 0;

    protected $table = 'dtb_authorization';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'regist_limit',
        'post_limit',
        'yahoo_info',
        'amazon_info',
        'monitoring',
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
     * update or create category authorization by user_id
     * @param  integer $userId
     * @param  array  $data
     * @return mixed
     */
    public function updateOrCreateCategoryByUserId($userId, $data = [])
    {
        $authorization = [
            'yahoo_info'   => static::DENY,
            'amazon_info'  => static::DENY,
            'monitoring'   => static::DENY,
            'regist_limit' => isset($data['regist_limit']) && $data['regist_limit'] ? $data['regist_limit'] : 0,
            'post_limit'   => isset($data['post_limit']) && $data['post_limit'] ? $data['post_limit'] : 0,
        ];
        if (isset($data['category']) && !empty($data['category'])) {
            foreach ($data['category'] as $key => $value) {
                switch ((Int) $value) {
                    case static::YAHOO_AUCTION_INFO:
                        $authorization['yahoo_info'] = static::ALLOW;
                        break;
                    case static::AMAZONE_INFO:
                        $authorization['amazon_info'] = static::ALLOW;
                        break;
                    case static::MONITORING_PRODUCT:
                        $authorization['monitoring'] = static::ALLOW;
                        break;
                    default:
                        break;
                }
            }
        }
        return $this->updateOrCreate(['user_id' => $userId], $authorization);
    }

    /**
     * check get amazone info allow
     * @return boolean
     */
    public function amazoneInfoAllow()
    {
        return (bool) $this->amazon_info;
    }

    /**
     * check get yahoo info allow
     * @return boolean
     */
    public function yahooInfoAllow()
    {
        return (bool) $this->yahoo_info;
    }

    /**
     * check monitoring
     * @return boolean
     */
    public function monitoringAllow()
    {
        return (bool) $this->monitoring;
    }
}
