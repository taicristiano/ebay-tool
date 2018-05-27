<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class UpdateProfitRequest extends Request
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
        return [
            'material_quantity' => 'integer|greate_than_or_equal_zero',
        ];
    }

    /**
     * message validate
     * @return array
     */
    public static function messagesValidates()
    {
        return [
            'material_quantity.integer' => trans('message.material quantity must be integer'),
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
