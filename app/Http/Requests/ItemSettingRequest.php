<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Lang;

class ItemSettingRequest extends Request
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
            'min_price' => 'required|numeric|greater_than_zero',
            'max_price' => 'required|numeric|greater_than_zero',
        ];
    }

    /**
     * message validate
     * @return array
     */
    public function messages()
    {
        return [
            'min_price.required'          => trans('validation.post-product.the_min_price_field_is_required'),
            'min_price.numeric'           => trans('validation.post-product.the_min_price_field_must_be_number'),
            'min_price.greater_than_zero' => trans('validation.post-product.the_min_price_field_must_be_greater_than_zero'),
            'max_price.required'          => trans('validation.post-product.the_max_price_field_is_required'),
            'max_price.numeric'           => trans('validation.post-product.the_max_price_field_must_be_number'),
            'max_price.greater_than_zero' => trans('validation.post-product.the_max_price_field_must_be_greater_than_zero'),
        ];
    }
}
