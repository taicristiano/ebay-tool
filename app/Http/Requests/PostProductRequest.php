<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class PostProductRequest extends Request
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
        $rules = [
            'dtb_item.item_name'      => 'required',
            // 'dtb_item.condition_name' => 'required',
            'dtb_item.condition_des'  => 'required',
            'dtb_item.category_id'    => 'required',
            'dtb_item.price'          => 'required|numeric|greate_than_zero',
            'dtb_item.buy_price'      => 'required|numeric|greate_than_zero',
        ];
        if ($data['dtb_item']['type'] == 2) {
            $rules['dtb_item.product_size'] = 'product_size';
            if (!empty($data['dtb_item[material_quantity]'])) {
                $rules['dtb_item.material_quantity'] = 'material_quantity';
            }
        }
        if (!empty($data['dtb_item_specifics'])) {
            foreach ($data['dtb_item_specifics'] as $key => $item) {
                $rules['dtb_item_specifics.' . $key . '.name']  = 'required';
                $rules['dtb_item_specifics.' . $key . '.value'] = 'required';
            }
        } else {
            $rules['dtb_item_specifics'] = 'required';
        }
        return $rules;
    }

    /**
     * message validate
     * @return array
     */
    public static function messagesValidates($data)
    {
        $messages = [
            'dtb_item.item_name.required'        => trans('validation.post-product.the_item_name_field_is_required'),
            'dtb_item.condition_name.required'   => trans('validation.post-product.the_condition_name_field_is_required'),
            'dtb_item.condition_des.required'    => trans('validation.post-product.the_condition_des_field_is_required'),
            'dtb_item.price.required'            => trans('validation.post-product.the_price_field_is_required'),
            'dtb_item.price.numeric'             => trans('validation.post-product.the_price_field_must_be_number'),
            'dtb_item.buy_price.required'        => trans('validation.post-product.the_buy_price_field_is_required'),
            'dtb_item.buy_price.numeric'         => trans('validation.post-product.the_buy_price_must_be_number'),
        ];
        if ($data['dtb_item']['type'] == 2) {
            $messages['dtb_item.product_size.product_size_format'] = trans('validation.post-product.the_product_size_is_required');
            if (!empty($data['dtb_item[material_quantity]'])) {
                $messages['dtb_item.material_quantity.numeric'] = trans('validation.post-product.the_material_quantity_field_must_be_number');
            }
        }
        if (!empty($data['dtb_item_specifics'])) {
            foreach ($data['dtb_item_specifics'] as $key => $item) {
                $messages['dtb_item_specifics.' . $key . '.name.required']  = trans('validation.post-product.the_item_specifics_name_field_is_required');
                $messages['dtb_item_specifics.' . $key . '.value.required'] = trans('validation.post-product.the_item_specifics_value_field_is_required');
            }
        } else {
            $messages['dtb_item_specifics'] = trans('validation.post-product.the_item_specifics_field_is_required');
        }

        return $messages;
    }

    /**
     * validate data
     * @param array
     * @return validator
     */
    public static function validateData($data = array())
    {
        $rules = self::rules($data);
        return Validator::make($data, $rules, self::messagesValidates($data));
    }
}
