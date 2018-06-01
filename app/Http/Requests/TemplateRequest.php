<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TemplateRequest extends FormRequest
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
            'title'   => 'required|max:255',
            'content' => 'required|max:500',
        ];
    }
}
