<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class CalculateProfitRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules($data)
    {
        if ($data['is_validate'] != 'false') {
            $rules = [
                'sell_price'        => 'required|numeric|greater_than_zero',
                'category_id'       => 'required',
            ];
            if ($data['is_update']) {
                $rules['buy_price'] = 'required|numeric|greater_than_zero';
                if ($data['type'] == 2) {
                    $rules['material_quantity'] = 'material_quantity';
                    $rules['product_size'] = 'product_size';
                }
            }
        } else {
            $rules = [];
        }
        return $rules;
    }

    /**
     * message validate
     * @return array
     */
    public static function messagesValidates()
    {
        return [
            'material_quantity.numeric' => trans('validation.post-product.the_material_quantity_field_must_be_number'),
            'sell_price.required'       => trans('validation.post-product.the_price_field_is_required'),
            'sell_price.numeric'        => trans('validation.post-product.the_price_field_must_be_number'),
            'buy_price.required'        => trans('validation.post-product.the_buy_price_field_is_required'),
            'buy_price.numeric'         => trans('validation.post-product.the_buy_price_must_be_number'),
        ];
    }

    /**
     * validate data
     * @param array
     * @return validator
     */
    public static function validateData($data = array())
    {
        $rules = self::rules($data);
        return Validator::make($data, $rules, self::messagesValidates());
    }
}
