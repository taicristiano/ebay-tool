<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use App\Models\Item;

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
            'dtb_item.item_name'        => 'required',
            'dtb_item.condition_des'    => 'required',
            'dtb_item.jan_upc'          => 'required',
            'dtb_item.price'            => 'required|numeric|greater_than_zero',
            'dtb_item.buy_price'        => 'required|numeric|greater_than_zero',
            'dtb_item.quantity'         => 'required|numeric|greater_than_zero',
            'dtb_item.ship_fee'         => 'required|numeric|greater_than_zero',
            'dtb_item.commodity_weight' => 'required|numeric|greater_than_zero',
        ];
        // if ($data['dtb_item']['type'] == Item::ORIGIN_TYPE_AMAZON) {
        $rules['dtb_item.height']            = 'nullable|numeric|greater_than_zero';
        $rules['dtb_item.width']             = 'nullable|numeric|greater_than_zero';
        $rules['dtb_item.length']            = 'nullable|numeric|greater_than_zero';
        $rules['dtb_item.material_quantity'] = 'nullable|numeric|greater_than_zero';
        // }
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
            'dtb_item.item_name.required'                 => trans('validation.post-product.the_item_name_field_is_required'),
            'dtb_item.condition_des.required'             => trans('validation.post-product.the_condition_des_field_is_required'),
            'dtb_item.jan_upc.required'                   => trans('validation.post-product.the_jan_upc_field_is_required'),
            'dtb_item.price.required'                     => trans('validation.post-product.the_sell_price_field_is_required'),
            'dtb_item.price.numeric'                      => trans('validation.post-product.the_sell_price_field_must_be_number'),
            'dtb_item.price.greater_than_zero'            => trans('validation.post-product.the_sell_price_field_must_be_greater_than_zero'),
            'dtb_item.buy_price.required'                 => trans('validation.post-product.the_buy_price_field_is_required'),
            'dtb_item.buy_price.numeric'                  => trans('validation.post-product.the_buy_price_field_must_be_number'),
            'dtb_item.buy_price.greater_than_zero'        => trans('validation.post-product.the_buy_price_field_must_be_greater_than_zero'),
            'dtb_item.quantity.required'                  => trans('validation.post-product.the_quantity_field_is_required'),
            'dtb_item.quantity.numeric'                   => trans('validation.post-product.the_quantity_field_must_be_number'),
            'dtb_item.quantity.greater_than_zero'         => trans('validation.post-product.the_quantity_field_must_be_greater_than_zero'),
            'dtb_item.ship_fee.required'                  => trans('validation.post-product.the_ship_fee_field_is_required'),
            'dtb_item.ship_fee.numeric'                   => trans('validation.post-product.the_ship_fee_field_must_be_number'),
            'dtb_item.ship_fee.greater_than_zero'         => trans('validation.post-product.the_ship_fee_field_must_be_greater_than_zero'),
            'dtb_item.commodity_weight.required'          => trans('validation.post-product.the_commodity_weight_field_is_required'),
            'dtb_item.commodity_weight.numeric'           => trans('validation.post-product.the_commodity_weight_field_must_be_number'),
            'dtb_item.commodity_weight.greater_than_zero' => trans('validation.post-product.the_commodity_weight_field_must_be_greater_than_zero'),
        ];
        // if ($data['dtb_item']['type'] == Item::ORIGIN_TYPE_AMAZON) {
        $messages['dtb_item.length.numeric']                      = trans('validation.post-product.the_length_product_field_must_be_number');
        $messages['dtb_item.length.greater_than_zero']            = trans('validation.post-product.the_length_product_field_must_be_greater_than_zero');
        $messages['dtb_item.width.numeric']                       = trans('validation.post-product.the_width_product_field_must_be_number');
        $messages['dtb_item.width.greater_than_zero']             = trans('validation.post-product.the_width_product_field_must_be_greater_than_zero');
        $messages['dtb_item.height.numeric']                      = trans('validation.post-product.the_height_product_field_must_be_number');
        $messages['dtb_item.height.greater_than_zero']            = trans('validation.post-product.the_height_product_field_must_be_greater_than_zero');
        $messages['dtb_item.material_quantity.numeric']           = trans('validation.post-product.the_material_quantity_field_must_be_number');
        $messages['dtb_item.material_quantity.greater_than_zero'] = trans('validation.post-product.the_material_quantity_field_must_be_greater_than_zero');
        // }
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
     * @return Illuminate\Support\Facades\Validator
     */
    public static function validateData($data = array())
    {
        $rules = self::rules($data);
        return Validator::make($data, $rules, self::messagesValidates($data));
    }
}
