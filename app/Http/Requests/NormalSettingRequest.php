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
            'paypal_fee_rate' => 'required|percent',
            'paypal_fixed_fee' => 'required|integer|greate_than_or_equal_zero',
            'ex_rate_diff' => 'required|integer',
            'gift_discount' => 'required|integer|min:1|max:100',
            'duration' => 'required',
            'quantity' => 'required|integer|min:1',
        ];
    }    

    /**
     * message validate
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
