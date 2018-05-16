<?php

namespace App\Http\Requests;

use App\Models\Authorization;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'type'          => [
                'required',
                Rule::in([
                    User::TYPE_NORMAL_USER,
                    User::TYPE_SUPER_ADMIN,
                    User::TYPE_GUEST_ADMIN,
                    User::TYPE_CANCELATION_USER,
                ]),
            ],
            'category'      => 'array',
            'category.*'    => Rule::in([
                Authorization::YAHOO_AUCTION_INFO,
                Authorization::AMAZONE_INFO,
                Authorization::MONITORING_PRODUCT,
            ]),
            'user_name'     => 'required|max:50',
            'name_kana'     => 'required|max:50',
            'introducer_id' => 'nullable|exists:' . (new User)->getTable() . ',id',
            'ebay_account'  => 'required|max:50',
            'start_date'    => 'nullable|date',
            'tel'           => 'nullable|max:15',
            'email'         => 'required|email|max:50|unique:dtb_user',
            'regist_limit'  => 'nullable|numeric|digits_between:1,10',
            'post_limit'    => 'nullable|numeric|digits_between:1,10',
            'password'      => 'required|confirmed|max:50',
            'memo'          => 'nullable|max:500',
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
