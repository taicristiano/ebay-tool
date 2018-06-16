<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use App\Models\Item;

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
                if ($data['type'] == Item::ORIGIN_TYPE_AMAZON) {
                    $rules['material_quantity'] = 'material_quantity';
                    $rules['height']            = 'nullable|numeric|greater_than_zero';
                    $rules['width']             = 'nullable|numeric|greater_than_zero';
                    $rules['length']            = 'nullable|numeric|greater_than_zero';
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
            'length.numeric'            => trans('validation.post-product.the_length_product_field_must_be_number'),
            'length.greater_than_zero'  => trans('validation.post-product.the_length_product_field_must_be_greater_than_zero'),
            'width.numeric'             => trans('validation.post-product.the_width_product_field_must_be_number'),
            'width.greater_than_zero'   => trans('validation.post-product.the_width_product_field_must_be_greater_than_zero'),
            'height.numeric'            => trans('validation.post-product.the_height_product_field_must_be_number'),
            'height.greater_than_zero'  => trans('validation.post-product.the_height_product_field_must_be_greater_than_zero'),
        ];
    }

    /**
     * validate data
     * @param array
     * @return Illuminate\Support\Facades\Validator
     */
    public static function validateData($data = array())
    {
        $rules = self::rules($data);
        return Validator::make($data, $rules, self::messagesValidates());
    }
}
