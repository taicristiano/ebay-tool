<?php

namespace App\Models;

class MtbStore extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mtb_store';

    protected $guarded = [];

    /**
     * get all store
     * @return array object
     */
    public function getAllStore()
    {
        return $this->select('id', 'name')
            ->get();
    }
}
