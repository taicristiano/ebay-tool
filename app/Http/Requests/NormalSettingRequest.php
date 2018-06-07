<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Lang;

class NormalSettingRequest extends Request
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
    public function rules()
    {
        return [
            'paypal_fee_rate'  => 'required|percent',
            'paypal_fixed_fee' => 'required|integer|greater_than_or_equal_zero',
            'ex_rate_diff'     => 'required|integer',
            'gift_discount'    => 'required|integer|min:1|max:100',
            'duration'         => 'required',
            'quantity'         => 'required|integer|min:1',
            'seller_id'        => 'required',
            'mws_auth_token'   => 'required',
            'mws_access_key'   => 'required',
            'mws_secret_key'   => 'required',
        ];
    }

    /**
     * message validate
     * @return array
     */
    public function messages()
    {
        return [
            'paypal_fee_rate.required' => Lang::get('validation.normal-setting.the_paypal_fee_rate_field_is_required'),
            'paypal_fee_rate.percent'  => Lang::get('validation.normal-setting.the_paypal_fee_rate_field_is_must_be_from_1_to_100'),
            
            'paypal_fixed_fee.required'                  => Lang::get('validation.normal-setting.the_paypal_fixed_fee_field_is_required'),
            'paypal_fixed_fee.integer'                   => Lang::get('validation.normal-setting.the_paypal_fixed_fee_field_must_be_an_integer'),
            'paypal_fixed_fee.greater_than_or_equal_zero' => Lang::get('validation.normal-setting.the_paypal_fixed_fee_field_must_be_grate_than_or_equal_zero'),
            
            'ex_rate_diff.required' => Lang::get('validation.normal-setting.the_ex_rate_diff_field_is_required'),
            'ex_rate_diff.integer'  => Lang::get('validation.normal-setting.the_ex_rate_diff_field_must_be_an_integer'),
            
            'gift_discount.required' => Lang::get('validation.normal-setting.the_gift_discount_field_is_required'),
            'gift_discount.integer'  => Lang::get('validation.normal-setting.the_gift_discount_field_must_be_an_integer'),
            'gift_discount.min'      => Lang::get('validation.normal-setting.the_gift_discount_field_is_must_be_from_1_to_100'),
            'gift_discount.max'      => Lang::get('validation.normal-setting.the_gift_discount_field_is_must_be_from_1_to_100'),
            
            'quantity.required' => Lang::get('validation.normal-setting.the_quantity_field_is_required'),
            'quantity.integer'  => Lang::get('validation.normal-setting.the_quantity_field_must_be_an_integer'),
            'quantity.min'      => Lang::get('validation.normal-setting.the_quantity_field_is_must_be_greate_than_zero'),

            'seller_id.required'      => Lang::get('validation.normal-setting.the_seller_id_field_is_required'),
            'mws_auth_token.required' => Lang::get('validation.normal-setting.the_mws_auth_token_field_is_required'),
            'mws_access_key.required' => Lang::get('validation.normal-setting.the_mws_access_key_field_is_required'),
            'mws_secret_key.required' => Lang::get('validation.normal-setting.the_mws_secret_key_field_is_required'),
        ];
    }
}
