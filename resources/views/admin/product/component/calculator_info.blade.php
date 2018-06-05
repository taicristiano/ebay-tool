<div class="box box-success" id="item-calculator-info">
    <div class="box-header with-border">@lang('view.product_details')</div>
    <div class="box-body">
        @if($data['istTypeAmazon'])
        <div class="form-group form-group-custom">
            <label for="dtb_item[product_size]">@lang('view.product_size')</label>
            {!! Form::text("dtb_item[product_size]", isset($data['dtb_item']['product_size']) ? $data['dtb_item']['product_size'] : '', ['class' => 'form-control product_size', 'id' => 'product_size', 'placeholder' => 'Example: 12x34x56']) !!}
            <p class="error-validate text-danger display-nones error-dtb_item_product_size"></p>
        </div>
        <div class="specific-itemssss">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <label for="dtb_item[commodity_weight]">@lang('view.commodity_weight') <span class="text-danger">(*)</span></label>
                <div class="form-group input-group">
                    {!! Form::text("dtb_item[commodity_weight]", isset($data['dtb_item']['commodity_weight']) ? $data['dtb_item']['commodity_weight'] : '', ['class' => 'specific-name form-control commodity_weight', 'readonly' => true, 'id' => 'commodity-weight']) !!}
                    <span class="input-group-addon background-disable">@lang('view.g')</span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <label for="dtb_item[material_quantity]">@lang('view.material_quantity')</span></label>
                <div class="form-group input-group">
                    {!! Form::text("dtb_item[material_quantity]", isset($data['dtb_item']['material_quantity']) ? $data['dtb_item']['material_quantity'] : '', ['class' => 'specific-value form-control', 'id' => 'material-quantity']) !!}
                    <span class="input-group-addon">@lang('view.g')</span>
                </div>
                <p class="error-validate text-danger display-nones error-dtb_item_material_quantity" id="error-material-quantity"></p>
            </div>
        </div>
        <div class="form-group form-group-custom setting-shipping-option">
            <label for="dtb_item[setting_shipping_option]">@lang('view.setting_shipping_option') <span class="text-danger">(*)</span></label>
            {!! Form::select("dtb_item[setting_shipping_option]", $data['setting_shipping_option'], isset($data['setting_shipping_selected']) ? $data['setting_shipping_selected'] : '', ['class' => 'form-control', 'id' => 'setting-shipping']) !!}
            {!! $errors->first("dtb_item[setting_shipping_option]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[setting_shipping_option]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group-custom">
            <label for="dtb_item[ship_fee]">@lang('view.ship_fee') <span class="text-danger">(*)</span></label>
            <div class="form-group input-group">
                {!! Form::text("dtb_item[ship_fee]", isset($data['dtb_item']['ship_fee']) ? $data['dtb_item']['ship_fee'] : '', ['class' => 'form-control', 'readonly' => true, 'id' => 'ship_fee']) !!}
                <span class="input-group-addon background-disable">@lang('view.man')</span>
            </div>
        </div>
        @endif
        <div class="form-group-custom">
            <label for="dtb_item[ebay_fee]">@lang('view.ebay_fee') <span class="text-danger">(*)</span></label>
            <div class="form-group input-group">
                {!! Form::text("dtb_item[ebay_fee]", isset($data['dtb_item']['ebay_fee']) ? $data['dtb_item']['ebay_fee'] : '', ['class' => 'form-control', 'readonly' => true, 'id' => 'ebay-fee']) !!}
                <span class="input-group-addon background-disable">@lang('view.man')</span>
            </div>
        </div>
        <div class="form-group-custom">
            <label for="dtb_item[paypal_fee]">@lang('view.paypal_fee') <span class="text-danger">(*)</span></label>
            <div class="form-group input-group">
                {!! Form::text("dtb_item[paypal_fee]", isset($data['dtb_item']['paypal_fee']) ? $data['dtb_item']['paypal_fee'] : '', ['class' => 'form-control', 'readonly' => true, 'id' => 'paypal-fee']) !!}
                <span class="input-group-addon background-disable">@lang('view.man')</span>
            </div>
        </div>
        <div class="form-group-custom">
            <label for="dtb_item[buy_price]">@lang('view.buy_price') <span class="text-danger">(*)</span></label>
            <div class="form-group input-group">
                {!! Form::text("dtb_item[buy_price]", !empty($data['dtb_item']['buy_price']) ? $data['dtb_item']['buy_price'] : '', ['class' => 'form-control', 'id' => 'buy_price']) !!}
                <span class="input-group-addon">@lang('view.man')</span>
            </div>
            <p class="error-validate text-danger display-nones error-dtb_item_buy_price"></p>
        </div>
        <div class="form-group-custom">
            <label for="dtb_item[profit]">@lang('view.profit')</label>
            <div class="form-group input-group">
                {!! Form::text("dtb_item[profit]", isset($data['dtb_item']['profit']) ? $data['dtb_item']['profit'] : '', ['class' => 'form-control', 'readonly' => true,'id' => 'profit']) !!}
                <span class="input-group-addon background-disable">@lang('view.man')</span>
            </div>
        </div>
    </div>
</div>
