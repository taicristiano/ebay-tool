<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom'               => [
        'attribute-name'        => [
            'rule-name' => 'custom-message',
        ],
        'user_name.required'    => '民名を入力してください。',
        'name_kana.required'    => 'フリガナを入力してください。',
        'ebay_account.required' => 'ebayアカウントを入力してください。',
        'email'                 => [
            'required' => 'メールアドレスを入力ください。',
            'email'    => 'メールアドレスが正しく入力してください。',
        ],
        'password'              => [
            'required'  => 'パスワードを入力してください。',
            'confirmed' => 'パスワードは統一しません。',
            'min'       => 'パスワードは:min英数字で入力してください。',
        ],
        'shipping_name'         => [
            'required' => '発送方法を入力してください。',
        ],
        'max_size'              => [
            'required' => '全辺合計を入力してください。',
        ],
        'side_max_size'         => [
            'required' => '一辺最長を入力してください。',
        ],
        'weight'                => [
            'required' => '重量を入力してください。',
        ],
        'ship_fee'              => [
            'required' => '送料を入力してください。',
        ],
        'title'                 => [
            'required' => 'タイトルを入力してください。',
        ],
        'content'               => [
            'required' => '内容を入力してください。',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
     */

    'attributes'           => [],

    'normal-setting'       => [
        'the_paypal_fee_rate_field_is_required'                       => 'Paypalの手数料率を入力してください。',
        'the_paypal_fee_rate_field_is_must_be_from_1_to_100'          => 'Paypalの手数料は1から100まで入力してください。',
        'the_paypal_fixed_fee_field_is_required'                      => 'Paypalの手数料の固定費を入力してください。',
        'the_paypal_fixed_fee_field_must_be_an_integer'               => 'Paypalの手数料の固定費は整数で入力してください。',
        'the_paypal_fixed_fee_field_must_be_grate_than_or_equal_zero' => 'Paypalの手数料の固定費は0以上で入力してください。',
        'the_ex_rate_diff_field_is_required'                          => 'Paypalの為替調整を入力してください。',
        'the_ex_rate_diff_field_must_be_an_integer'                   => 'Paypalの為替調整は整数で入力してください。',
        'the_gift_discount_field_is_required'                         => 'Amazonのギフト件割引率を入力してください。',
        'the_gift_discount_field_must_be_an_integer'                  => 'Amazonのギフト件割引率は整数で入力してください。',
        'the_gift_discount_field_is_must_be_from_1_to_100'            => 'Amazonのギフト件割引率は1から100まで入力してください。',
        'the_quantity_field_is_required'                              => '個数を入力してください。',
        'the_quantity_field_must_be_an_integer'                       => '個数は整数で入力してください。',
        'the_quantity_field_is_must_be_greate_than_zero'              => '個数は0以上で入力してください。',
        'the_seller_id_field_is_required'                             => 'MWSの出品者IDを入力してください。',
        'the_mws_auth_token_field_is_required'                        => 'MWS認証トークンを入力してください。',
        'the_mws_access_key_field_is_required'                        => 'MWSのアクセスキーを入力してください。',
        'the_mws_secret_key_field_is_required'                        => 'MWSの秘密キーを入力してください。',
        'the_paypal_email_field_is_required'                          => 'the_paypal_email_field_is_required',
        'the_paypal_email_field_must_be_format_email'                 => 'the_paypal_email_field_must_be_format_email',
    ],

    'post-product' => [
        'the_item_name_field_is_required'                       => 'the_item_name_field_is_required',
        'the_condition_des_field_is_required'                   => 'the_condition_des_field_is_required',
        'the_sell_price_field_is_required'                      => 'the_sell_price_field_is_required',
        'the_sell_price_field_must_be_number'                   => 'the_sell_price_field_must_be_number',
        'the_sell_price_field_must_be_greater_than_zero'        => 'the_sell_price_field_must_be_greater_than_zero',
        'the_buy_price_field_is_required'                       => 'the_buy_price_field_is_required',
        'the_buy_price_field_must_be_number'                    => 'the_buy_price_field_must_be_number',
        'the_buy_price_field_must_be_greater_than_zero'         => 'the_buy_price_field_must_be_greater_than_zero',
        'the_quantity_field_is_required'                        => 'the_quantity_field_is_required',
        'the_quantity_field_must_be_number'                     => 'the_quantity_must_be_number',
        'the_quantity_field_must_be_greater_than_zero'          => 'the_quantity_field_must_be_greater_than_zero',
        'the_ship_fee_field_is_required'                        => 'the_ship_fee_field_is_required',
        'the_ship_fee_field_must_be_number'                     => 'the_ship_fee_must_be_number',
        'the_ship_fee_field_must_be_greater_than_zero'          => 'the_ship_fee_field_must_be_greater_than_zero',
        'the_commodity_weight_field_is_required'                => 'the_commodity_weight_field_is_required',
        'the_commodity_weight_field_must_be_number'             => 'the_commodity_weight_must_be_number',
        'the_commodity_weight_field_must_be_greater_than_zero'  => 'the_commodity_weight_field_must_be_greater_than_zero',
        'the_item_specifics_name_field_is_required'             => 'the_item_specifics_name_field_is_required',
        'the_item_specifics_value_field_is_required'            => 'the_item_specifics_value_field_is_required',
        'the_item_specifics_field_is_required'                  => 'the_item_specifics_field_is_required',
        'the_length_product_field_must_be_number'               => 'the_length_product_field_must_be_number',
        'the_length_product_field_must_be_greater_than_zero'    => 'the_length_product_field_must_be_greater_than_zero',
        'the_width_product_field_must_be_number'                => 'the_width_product_field_must_be_number',
        'the_width_product_field_must_be_greater_than_zero'     => 'the_width_product_field_must_be_greater_than_zero',
        'the_height_product_field_must_be_number'               => 'the_height_product_field_must_be_number',
        'the_height_product_field_must_be_greater_than_zero'    => 'the_height_product_field_must_be_greater_than_zero',
        'the_material_quantity_field_must_be_number'            => 'the_material_quantity_field_must_be_number',
        'the_material_quantity_field_must_be_greater_than_zero' => 'the_material_quantity_field_must_be_greater_than_zero',
        'the_jan_upc_field_is_required'                         => 'the_jan_upc_field_is_required',
        'the_min_price_field_is_required'                       => 'the_min_price_field_is_required',
        'the_min_price_field_must_be_number'                    => 'the_min_price_must_be_number',
        'the_min_price_field_must_be_greater_than_zero'         => 'the_min_price_field_must_be_greater_than_zero',
        'the_max_price_field_is_required'                       => 'the_max_price_field_is_required',
        'the_max_price_field_must_be_number'                    => 'the_max_price_must_be_number',
        'the_max_price_field_must_be_greater_than_zero'         => 'the_max_price_field_must_be_greater_than_zero',
    ]
];
