<?php

namespace App\Models;

class ShippingFee extends AbstractModel
{
    protected $table = 'dtb_shipping_fee';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shipping_id',
        'weight',
        'ship_fee',
    ];
}
