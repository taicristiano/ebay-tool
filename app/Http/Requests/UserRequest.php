<?php

namespace App\Http\Requests;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isSuperAdmin();
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
        $rules = [
            'user_name'    => 'required|max:50',
            'name_kana'    => 'required|max:50',
            'tel'          => 'nullable|max:15',
            'start_date'   => 'nullable|date',
            'ebay_account' => 'required|max:50',
            'email'        => 'required|email|max:50|unique:dtb_user',
            'password'     => 'required|confirmed|max:50',
        ];
        if ($req->id) {
            $rules['password'] = 'nullable|confirmed|max:50';
            $rules['email']    = 'required|email|max:50|unique:dtb_user,id,' . $req->id;
        }
        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $formData = $validator->getData();
            if (isset($formData['introducer_id'])) {
                session()->flash('introducer', User::getIntroducerOption($formData['introducer_id']));
            }
        });
    }
}
