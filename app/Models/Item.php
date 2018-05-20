<?php

namespace App\Models;

class Item extends AbstractModel
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_product';

    protected $guarded = [];

    const NAME_DURATION_1_DAY = '1日間';
    const NAME_DURATION_3_DAY = '3日間';
    const NAME_DURATION_5_DAY = '5日間';
    const NAME_DURATION_7_DAY = '7日間';
    const NAME_DURATION_10_DAY = '10日間';
    const NAME_DURATION_14_DAY = '14日間';
    const NAME_DURATION_21_DAY = '21日間';
    const NAME_DURATION_30_DAY = '30日間';
    const NAME_DURATION_60_DAY = '60日間';
    const NAME_DURATION_90_DAY = '90日間';
    const NAME_DURATION_120_DAY = '120日間';

    const VALUE_DURATION_1_DAY = 'Days_1';
    const VALUE_DURATION_3_DAY = 'Days_3';
    const VALUE_DURATION_5_DAY = 'Days_5';
    const VALUE_DURATION_7_DAY = 'Days_7';
    const VALUE_DURATION_10_DAY = 'Days_10';
    const VALUE_DURATION_14_DAY = 'Days_14';
    const VALUE_DURATION_21_DAY = 'Days_21';
    const VALUE_DURATION_30_DAY = 'Days_30';
    const VALUE_DURATION_60_DAY = 'Days_60';
    const VALUE_DURATION_90_DAY = 'Days_90';
    const VALUE_DURATION_120_DAY = 'Days_120';

    const ORIGIN_TYPE_YAHOO_AUCTION = 1;
    const ORIGIN_TYPE_AMAZON = 2;

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
            self::VALUE_DURATION_1_DAY => self::NAME_DURATION_1_DAY,
            self::VALUE_DURATION_3_DAY => self::NAME_DURATION_3_DAY,
            self::VALUE_DURATION_5_DAY => self::NAME_DURATION_5_DAY,
            self::VALUE_DURATION_7_DAY => self::NAME_DURATION_7_DAY,
            self::VALUE_DURATION_10_DAY => self::NAME_DURATION_10_DAY,
            self::VALUE_DURATION_14_DAY => self::NAME_DURATION_14_DAY,
            self::VALUE_DURATION_21_DAY => self::NAME_DURATION_21_DAY,
            self::VALUE_DURATION_30_DAY => self::NAME_DURATION_30_DAY,
            self::VALUE_DURATION_60_DAY => self::NAME_DURATION_60_DAY,
            self::VALUE_DURATION_90_DAY => self::NAME_DURATION_90_DAY,
            self::VALUE_DURATION_120_DAY => self::NAME_DURATION_120_DAY,
        ];
    }
}
