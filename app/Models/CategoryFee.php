<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryFee extends AbstractModel
{
    use SoftDeletes;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mtb_category_fee';

    protected $guarded = [];


    public function getCategoryFeeByCategoryId($categoryId)
    {
        return $this->where('category_id', $categoryId)
            ->first();
    }

    public function getFeeForStoreId($storeId)
    {
        return $arrayFee[$storeId];
    }
}
