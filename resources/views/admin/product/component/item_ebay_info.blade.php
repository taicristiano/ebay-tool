<div class="box box-success" id="item-ebay-content">
    <div class="box-header with-border">@lang('view.product_details')</div>
    <div class="box-body">
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">@lang('view.product_name') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            {!! Form::hidden("dtb_item[category_id]", isset($data['dtb_item']['category_id']) ? $data['dtb_item']['category_id'] : '', ['id' => 'category_id']) !!}
            <label for="dtb_item[category_name]">@lang('view.category') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[category_name]", old("dtb_item['category_name']", isset($data['dtb_item']['category_name']) ? $data['dtb_item']['category_name'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
            {!! $errors->first("dtb_item[category_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[category_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">@lang('view.JAN/UPC') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            {!! Form::hidden("dtb_item[condition_id]", isset($data['dtb_item']['condition_id']) ? $data['dtb_item']['condition_id'] : '') !!}
            <label for="dtb_item[condition_name]">@lang('view.product_name') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[condition_name]", old("dtb_item[condition_name]", isset($data['dtb_item']['condition_name']) ? $data['dtb_item']['condition_name'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[condition_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[condition_name]") .'</p>
            ' : ''!!}
        </div>
        <p>@lang('view.specifications')</p>
        <hr>
        @foreach($data['dtb_item_specifics'] as $key => $value)
        <div class="specific-item">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    {!! Form::text("dtb_item_specifics[$key][name]", old("dtb_item_specifics[$key][name]", isset($data['dtb_item_specifics'][$key]['name']) ? $data['dtb_item_specifics'][$key]['name'] : ''), ['class' => 'specific-name form-control']) !!}
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group input-group">
                    {!! Form::text("dtb_item_specifics[$key][value]", old("dtb_item_specifics[$key][value]", isset($data['dtb_item_specifics'][$key]['value']) ? $data['dtb_item_specifics'][$key]['value'] : ''), ['class' => 'specific-value form-control']) !!}
                    <span class="input-group-addon">
                        <a class="delete-specific"><i class="btn btn-danger fa fa-trash btn-fa"></i></a>
                    </span>
                </div>
            </div>
        </div>
        @endforeach
        <div class="text-center" id="div-add-specific">
            <button type="button" class="btn btn-info fa" id="add-specific"><i class="fa fa-plus fa-fw"></i></a></button>
        </div>
        <div class="display-none" id="specific-item-none">
            <div class="specific-item">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <input class="form-control specific-name" name="" type="text">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group input-group">
                        <input class="form-control specific-value" name="" type="text">
                        <span class="input-group-addon">
                            <a class="delete-specific"><i class="btn btn-danger fa fa-trash btn-fa"></i></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <p>@lang('view.sale_details')</p>
        <hr>
        <div class="form-group form-group-custom">
            <label for="dtb_item[price]">@lang('view.sell_price') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[price]", old("dtb_item[price]", isset($data['dtb_item']['price']) ? $data['dtb_item']['price'] : ''), ['class' => 'form-control', 'id' => 'sell_price']) !!}
            {!! $errors->first("dtb_item[price]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[price]") .'</p>
            ' : ''!!}
        </div>
        <p>@lang('view.setting_value')</p>
        <hr>
        <div class="form-group form-group-custom">
            <label for="dtb_item[duration]">@lang('view.sale_period') <span class="text-danger">(*)</span></label>
            {!! Form::select("dtb_item[duration]", $data['duration']['option'], old("dtb_item[duration]", isset($data['dtb_item']['duration']) ? $data['dtb_item']['duration'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[duration]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[duration]") .'</p>
            ' : ''!!}
        </div>

        <div class="form-group form-group-custom">
            <label for="dtb_item[quantity]">@lang('view.quantity') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[quantity]", old("dtb_setting[quantity]", isset($data['dtb_item']['quantity']) ? $data['dtb_item']['quantity'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
            {!! $errors->first("dtb_item[quantity]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[quantity]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[shipping_policy_id]">@lang('view.shipping_policy') <span class="text-danger">(*)</span></label>
            {!! Form::select("dtb_item[shipping_policy_id]", $data['dtb_setting_policies']['shipping'], old("dtb_item[shipping_policy_id]", isset($data['dtb_setting_policies']['shipping']) ? $data['dtb_setting_policies']['shipping'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[shipping_policy_id]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[shipping_policy_id]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[payment_policy_id]">@lang('view.payment_policy') <span class="text-danger">(*)</span></label>
            {!! Form::select("dtb_item[payment_policy_id]", $data['dtb_setting_policies']['payment'], old("dtb_item[payment_policy_id]", isset($data['dtb_setting_policies']['payment']) ? $data['dtb_setting_policies']['payment'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[payment_policy_id]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[payment_policy_id]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[return_policy_id]">@lang('view.return_policy') <span class="text-danger">(*)</span></label>
            {!! Form::select("dtb_item[return_policy_id]", $data['dtb_setting_policies']['return'], old("dtb_item[return_policy_id]", isset($data['dtb_setting_policies']['return']) ? $data['dtb_setting_policies']['return'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[return_policy_id]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[return_policy_id]") .'</p>
            ' : ''!!}
        </div>
    </div>
</div>
