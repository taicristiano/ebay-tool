<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    // define shared methods

    /**
	 * get field list
	 * @return array
	 */
	public function getFieldList()
	{
		return $this->fillable;
	}

	/**
	 * find item by id
	 * @param  integer $id
	 * @return object
	 */
	public function findById($id)
	{
		return $this->find($id);
	}
}
