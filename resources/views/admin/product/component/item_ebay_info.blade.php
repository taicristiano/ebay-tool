<div class="box box-success" id="item-ebay-content">
    <div class="box-header with-border">@lang('view.product_details')</div>
    <div class="box-body">
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">@lang('view.product_name') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : '', ['class' => 'form-control', 'id' => 'item_name']) !!}
            <p class="error-validate text-danger display-nones error-dtb_item_item_name"></p>
        </div>
        <div class="form-group form-group-custom">
            {!! Form::hidden("dtb_item[category_id]", isset($data['dtb_item']['category_id']) ? $data['dtb_item']['category_id'] : '', ['id' => 'category_id']) !!}
            <label for="dtb_item[category_name]">@lang('view.category') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[category_name]", isset($data['dtb_item']['category_name']) ? $data['dtb_item']['category_name'] : '', ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[jan_upc]">@lang('view.JAN/UPC') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[jan_upc]", isset($data['dtb_item']['jan_upc']) ? $data['dtb_item']['jan_upc'] : '', ['class' => 'form-control', 'readonly' => true]) !!}
        </div>
        <div class="form-group form-group-custom">
            {!! Form::hidden("dtb_item[condition_id]", isset($data['dtb_item']['condition_id']) ? $data['dtb_item']['condition_id'] : '') !!}
            <label for="dtb_item[condition_name]">@lang('view.product_name') <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[condition_name]", isset($data['dtb_item']['condition_name']) ? $data['dtb_item']['condition_name'] : '', ['class' => 'form-control', 'id' => 'condition_name']) !!}
            <p class="error-validate text-danger display-nones error-dtb_item_condition_name"></p>
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[condition_des]">@lang('view.condition_des') <span class="text-danger">(*)</span></label>
            {{ Form::textarea('dtb_item[condition_des]', isset($data['dtb_item']['condition_des']) ? $data['dtb_item']['condition_des'] : '', ['size' => '30x5', 'class' => 'form-control', 'id' => 'condition_des']) }}
            <p class="error-validate text-danger display-nones error-dtb_item_condition_des"></p>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">@lang('view.specifications')</div>
            <div class="box-body">
                @foreach($data['dtb_item_specifics'] as $key => $value)
                <div class="specific-item row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            {!! Form::text("dtb_item_specifics[$key][name]", isset($data['dtb_item_specifics'][$key]['name']) ? $data['dtb_item_specifics'][$key]['name'] : '', ['class' => 'specific-name form-control']) !!}
                        </div>
                        <p class="error-name error-validate-specifics text-danger display-nones"><span class="error-dtb_item_specifics_{{$key}}_name"></span></p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group input-group">
                            {!! Form::text("dtb_item_specifics[$key][value]", isset($data['dtb_item_specifics'][$key]['value']) ? $data['dtb_item_specifics'][$key]['value'] : '', ['class' => 'specific-value form-control']) !!}
                            <span class="input-group-addon">
                                <a class="delete-specific"><i class="btn btn-danger fa fa-trash btn-fa"></i></a>
                            </span>
                        </div>
                        <p class="error-value error-validate-specifics text-danger display-nones"><span class="error-dtb_item_specifics_{{$key}}_value"></span></p>
                    </div>
                </div>
                @endforeach
                <div class="text-center" id="div-add-specific">
                    <p class="error-value error-validate-specifics text-danger display-nones"><span class="error-dtb_item_specifics"></span></p>
                    <button type="button" class="btn btn-info fa" id="add-specific"><i class="fa fa-plus fa-fw"></i></a></button>
                </div>
                <div class="display-none" id="specific-item-none">
                    <div class="specific-item row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <input class="form-control specific-name" name="" type="text">
                            </div>
                            <p class="error-name error-validate-specifics text-danger display-nones"><span></span></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group input-group">
                                <input class="form-control specific-value" name="" type="text">
                                <span class="input-group-addon">
                                    <a class="delete-specific"><i class="btn btn-danger fa fa-trash btn-fa"></i></a>
                                </span>
                            </div>
                            <p class="error-value error-validate-specifics text-danger display-nones"><span></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">@lang('view.sale_details')</div>
            <div class="box-body">
                <div class="form-group-custom">
                    <label for="dtb_item[price]">@lang('view.sell_price') <span class="text-danger">(*)</span></label>
                    <div class="form-group input-group">
                        {!! Form::text("dtb_item[price]", isset($data['dtb_item']['price']) ? $data['dtb_item']['price'] : '', ['class' => 'form-control', 'id' => 'sell_price']) !!}
                        <span class="input-group-addon">@lang('view.man')</span>
                    </div>
                    <p class="error-validate text-danger display-nones error-dtb_item_price"></p>
                </div>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">@lang('view.setting_value')</div>
            <div class="box-body">
                <div class="form-group form-group-custom">
                    <label for="dtb_item[duration]">@lang('view.sale_period') <span class="text-danger">(*)</span></label>
                    {!! Form::select("dtb_item[duration]", $data['duration']['option'], isset($data['dtb_item']['duration']) ? $data['dtb_item']['duration'] : '', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group form-group-custom">
                    <label for="dtb_item[quantity]">@lang('view.quantity') <span class="text-danger">(*)</span></label>
                    {!! Form::text("dtb_item[quantity]", isset($data['dtb_item']['quantity']) ? $data['dtb_item']['quantity'] : '', ['class' => 'form-control', 'readonly' => true]) !!}
                </div>
                <div class="form-group form-group-custom">
                    <label for="dtb_item[shipping_policy_id]">@lang('view.shipping_policy') <span class="text-danger">(*)</span></label>
                    {!! Form::select("dtb_item[shipping_policy_id]", $data['dtb_setting_policies']['shipping'], isset($data['dtb_setting_policies']['shipping']) ? $data['dtb_setting_policies']['shipping'] : '', ['class' => 'form-control']) !!}
                </div>
                <div class="form-group form-group-custom">
                    <label for="dtb_item[payment_policy_id]">@lang('view.payment_policy') <span class="text-danger">(*)</span></label>
                    {!! Form::select("dtb_item[payment_policy_id]", $data['dtb_setting_policies']['payment'], isset($data['dtb_setting_policies']['payment']) ? $data['dtb_setting_policies']['payment'] : '', ['class' => 'form-control']) !!}
                </div>
                <div class="form-group form-group-custom">
                    <label for="dtb_item[return_policy_id]">@lang('view.return_policy') <span class="text-danger">(*)</span></label>
                    {!! Form::select("dtb_item[return_policy_id]", $data['dtb_setting_policies']['return'], isset($data['dtb_setting_policies']['return']) ? $data['dtb_setting_policies']['return'] : '', ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
