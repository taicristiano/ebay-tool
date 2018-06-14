<?php

namespace App\Models;

class ItemSpecific extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_item_specifics';

    protected $guarded = [];

    /**
     * get by item id
     * @param  integer $itemId
     * @return array
     */
    public function getByItemId($itemId)
    {
        return $this->whereItemId($itemId)
            ->get()
            ->toArray();
    }
}
