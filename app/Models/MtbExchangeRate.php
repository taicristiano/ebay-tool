<?php

namespace App\Models;

class MtbExchangeRate extends AbstractModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mtb_exchange_rate';

    protected $guarded = [];

    /**
     * get exchange rate latest
     * @return object
     */
    public function getExchangeRateLatest()
    {
        return $this->orderBy('created_at', 'desc')
            ->first();
    }
}
