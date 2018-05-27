<?php

namespace App\Models;

class Item extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_item';

    protected $guarded = [];

    const NAME_DURATION_3_DAY = '3日間';
    const NAME_DURATION_5_DAY = '5日間';
    const NAME_DURATION_7_DAY = '7日間';
    const NAME_DURATION_10_DAY = '10日間';
    const NAME_DURATION_30_DAY = '30日間';

    const VALUE_DURATION_3_DAY = 'Days_3';
    const VALUE_DURATION_5_DAY = 'Days_5';
    const VALUE_DURATION_7_DAY = 'Days_7';
    const VALUE_DURATION_10_DAY = 'Days_10';
    const VALUE_DURATION_30_DAY = 'Days_30';

    const ORIGIN_TYPE_YAHOO_AUCTION = 1;
    const ORIGIN_TYPE_AMAZON = 2;

    const SESSION_KEY_PRODUCT_INFO = 'product-info';

    /**
     * get origin type yahoo auction
     * @return integer
     */
    public function getOriginTypeYahooAuction()
    {
        return self::ORIGIN_TYPE_YAHOO_AUCTION;
    }

    /**
     * get origin type amazon
     * @return integer
     */
    public function getOriginTypeAmazon()
    {
        return self::ORIGIN_TYPE_AMAZON;
    }

    /**
     * get origin type
     * @return array
     */
    public function getOriginType()
    {
        return [
            $this->getOriginTypeYahooAuction() => trans('view.yahoo_auction'),
            $this->getOriginTypeAmazon() => trans('view.amazon'),
        ];
    }

    /**
     * get duration option
     * @return array
     */
    public function getDurationOption()
    {
        return [
            self::VALUE_DURATION_3_DAY => self::NAME_DURATION_3_DAY,
            self::VALUE_DURATION_5_DAY => self::NAME_DURATION_5_DAY,
            self::VALUE_DURATION_7_DAY => self::NAME_DURATION_7_DAY,
            self::VALUE_DURATION_10_DAY => self::NAME_DURATION_10_DAY,
            self::VALUE_DURATION_30_DAY => self::NAME_DURATION_30_DAY,
        ];
    }
}
