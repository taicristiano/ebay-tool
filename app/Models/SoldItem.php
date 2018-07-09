<?php

namespace App\Models;

class SoldItem extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dtb_sold_items';

    protected $guarded = [];

    const AUTO_BY_FLG_NOT_YET = 0;
    const AUTO_BY_FLG_DONE = 1;

    /**
     * get flag auto done
     * @return integer
     */
    public function getFlagAutoByFlgDone()
    {
    	return self::AUTO_BY_FLG_DONE;
    }
}
