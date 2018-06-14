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

    const STATUS_SELLING = 1;
    const STATUS_CANCEL  = 2;
    const STATUS_EXPIRE  = 3;
    const STATUS_SOLD    = 4;

    /**
     * get status selling
     * @return integer
     */
    public function getStatusSelling()
    {
        return self::STATUS_SELLING;
    }

    /**
     * get status cancel
     * @return integer
     */
    public function getStatusCancel()
    {
        return self::STATUS_CANCEL;
    }

    /**
     * get status expire
     * @return integer
     */
    public function getStatusExpire()
    {
        return self::STATUS_EXPIRE;
    }

    /**
     * get status sold
     * @return integer
     */
    public function getStatusSold()
    {
        return self::STATUS_SOLD;
    }

    /**
     * Get the images for the blog item.
     */
    public function images()
    {
        return $this->hasMany('App\Models\ItemImage', 'item_id');
    }

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

    /**
     * get condition id list
     * @return array
     */
    public function getConditionIdList()
    {
        return [
            1000 => 'New',
            1500 => 'New other (see details)',
            1750 => 'New with defects',
            2000 => 'Manufacturer refurbished',
            2500 => 'Seller refurbished',
            3000 => 'Used',
            4000 => 'Very Good',
            5000 => 'Good',
            6000 => 'Acceptable',
            7000 => 'For parts or not working',
        ];
    }

    /**
     * get conditionNameById
     * @param  integer $conditionId
     * @return string
     */
    public function getConditionNameById($conditionId)
    {
        return $this->getConditionIdList()[$conditionId];
    }

    /**
     * get list product
     * @param  array $input
     * @param  integer $userId
     * @return array object
     */
    public function getListProduct($input, $userId)
    {
        $condition = $this->with('images');
        if (!empty($input['search'])) {
            $condition = $condition->where(function ($query) use  ($input) {
                $query->orWhere('item_name', 'LIKE', '%' . $input['search'] . '%');
                $query->orWhere('original_id', 'LIKE', '%' . $input['search'] . '%');
            });
        }
        return $condition
            ->whereUserId($userId)
            ->whereStatus($this->getStatusSelling())
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * get data export csv
     * @param $integer $userId
     * @return array object
     */
    public function getDataExportCsv($userId)
    {
        return $this->whereUserId($userId)
            ->whereStatus($this->getStatusSelling())
            ->get()
            ->toArray();
    }

    /**
     * update
     * @param  integer $id
     * @param  array $data
     * @return boolean
     */
    public function updateItem($id, $data)
    {
        return $this->find($id)
            ->update($data);
    }

    /**
     * end list item
     * @param  array $itemIds
     * @return boolean
     */
    public function endListItem($itemIds)
    {
        return $this->whereIn('item_id', $itemIds)
            ->update(['status' => $this->getStatusCancel()]);
    }

    /**
     * find by id
     * @param  integer $id
     * @return object
     */
    public function findById($id)
    {
        return $this->find($id)->toArray();
    }
}
