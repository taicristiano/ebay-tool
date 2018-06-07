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

    /**
     * search category
     * @param  array $input
     * @return array
     */
    public function search($input)
    {
        $condition = $this->select('category_id as id', 'category_path as text');
        if (isset($input['category_path'])) {
            $condition = $condition->where('category_path', 'LIKE', '%' . $input['category_path'] . '%');
        }
        $result = $condition->skip(($input['page'] - 1) * $input['limit'])
            ->limit($input['limit']);
        return [
            'results'        => $result->get()->toArray(),
            'count_filtered' => $result->count(),
        ];
    }

    /**
     * get first item
     * @return object
     */
    public function getFirstItem()
    {
        return $this->select('category_id', 'category_path')
            ->first();
    }
}
