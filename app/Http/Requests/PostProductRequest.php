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
        dd($data);
        $rules = [
            'dtb_item.item_name' => 'required',
            'dtb_item.condition_name' => 'required',
            'dtb_item.condition_des' => 'required',
            'dtb_item.price' => 'required',
        ];
        foreach ($data['dtb_item_specifics'] as $key => $item) {
            $rules['dtb_item_specifics.' . $key . '.name'] = 'required';
            $rules['dtb_item_specifics.' . $key . '.value'] = 'required';
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
            // 'dtb_item.item_name.integer' => trans('message.material quantity must be integer'),
            'dtb_item[item_name].required' => 'test',
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
