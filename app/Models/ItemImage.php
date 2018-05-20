<?php

namespace App\Models;

class ItemImage extends AbstractModel
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_item_images';

    protected $guarded = [];

    public function updateItemImageById($itemId, $data)
    {
        return $this->where('id', $itemId)
            ->update($data);
    }
}
