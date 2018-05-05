<?php

namespace App\Models;

class MtbStore extends AbstractModel
{
    protected $table = 'mtb_store';

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
