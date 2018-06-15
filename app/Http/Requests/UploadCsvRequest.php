<?php

namespace App\Http\Requests;

class UploadCsvRequest extends Request
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
            'file_csv' => 'required|mimes:csv,txt',
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
