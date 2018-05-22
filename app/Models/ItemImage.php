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

    const PATH_UPLOAD_FILE = 'public/upload/item-images';

    /**
     * update item image by id
     * @param  integer $itemId
     * @param  array $data
     * @return boolean
     */
    public function updateItemImageById($itemId, $data)
    {
        return $this->where('id', $itemId)
            ->update($data);
    }

    /**
     * get path upload file
     * @return string
     */
    public function getPathUploadFile()
    {
        return self::PATH_UPLOAD_FILE;
    }
}
