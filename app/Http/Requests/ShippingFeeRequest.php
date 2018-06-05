<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;

class ShippingFeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $req)
    {
        if ($req->isMethod('GET')) {
            return [];
        }
        return [
            'weight'   => 'required|numeric|digits_between:0,10',
            'ship_fee' => 'required|numeric|digits_between:0,10',
        ];
    }
}
