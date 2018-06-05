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

    const PATH_UPLOAD_FILE = 'upload/item-images/';
    const FULL_PATH_UPLOAD_FILE = 'app/public/upload/item-images/';
    const PATH_STORAGE_FILE = 'storage/upload/item-images/';

    const SESSION_KEY_IMAGE_FROM_API = 'image-from-api';

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

    /**
     * get full upload file
     * @return string
     */
    public function getFullPathUploadFile()
    {
        return self::FULL_PATH_UPLOAD_FILE;
    }

    /**
     * get path storage file
     * @return string
     */
    public function getPathStorageFile()
    {
        return self::PATH_STORAGE_FILE;
    }
}
