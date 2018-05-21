<div class="box box-success" id="item-calculator-info">
    <div class="box-header with-border">■製品詳細</div>
    <div class="box-body">
        @if($data['istTypeAmazon'])
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">商品サイズ <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[product_size]", old("data_amazon[product_size]", isset($data['data_amazon']['product_size']) ? $data['data_amazon']['product_size'] : ''), ['class' => 'form-control product_size']) !!}
            {!! $errors->first("data_amazon[product_size]") ? '
            <p class="text-danger">'. $errors->first("data_amazon[product_size]") .'</p>
            ' : ''!!}
        </div>
        <div class="specific-itemssss">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="dtb_item[commodity_weight]">商品重量 <span class="text-danger">(*)</span></label>
                    {!! Form::text("dtb_item[commodity_weight]", old("data_amazon[commodity_weight]", isset($data['data_amazon']['commodity_weight']) ? $data['data_amazon']['commodity_weight'] : ''), ['class' => 'specific-name form-control commodity_weight', 'readonly' => true, 'id' => 'commodity-weight']) !!}
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                    <label for="dtb_item[material_quantity]">梱包資材量 <span class="text-danger">(*)</span></label>
                <div class="form-group input-group">
                    {!! Form::text("dtb_item[material_quantity]", old("dtb_item[material_quantity]", isset($data['dtb_item']['material_quantity']) ? $data['dtb_item']['material_quantity'] : ''), ['class' => 'specific-value form-control', 'id' => 'material-quantity']) !!}
                    <span class="input-group-addon">g</span>
                </div>
                <p class="text-danger display-none" id="error-material-quantity">@lang('message.material quantity must be integer')</p>
            </div>
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[duration]">発送方法 <span class="text-danger">(*)</span></label>
            {!! Form::select("dtb_item[duration]", $data['setting_shipping_option'], old("dtb_item[duration]", isset($data['duration']['value']) ? $data['duration']['value'] : ''), ['class' => 'form-control', 'id' => 'setting-shipping']) !!}
            {!! $errors->first("dtb_item[duration]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[duration]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[ship_fee]">送料 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[ship_fee]", old("dtb_item[ship_fee]", isset($data['ship_fee']) ? $data['ship_fee'] : ''), ['class' => 'form-control', 'readonly' => true, 'id' => 'ship_fee']) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        @endif
        <div class="form-group form-group-custom">
            <label for="dtb_item[ebay_fee]">販売手数料 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[ebay_fee]", old("dtb_item[ebay_fee]", isset($data['ebay_fee']) ? $data['ebay_fee'] : ''), ['class' => 'form-control', 'readonly' => true, 'id' => 'ebay-fee']) !!}
            {!! $errors->first("dtb_item[ebay_fee]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[ebay_fee]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[paypal_fee]">paypal手数料 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[paypal_fee]", old("dtb_item[paypal_fee]", isset($data['paypal_fee']) ? $data['paypal_fee'] : ''), ['class' => 'form-control', 'readonly' => true, 'id' => 'paypal-fee']) !!}
            {!! $errors->first("dtb_item[paypal_fee]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[paypal_fee]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[buy_price]">仕入れ元価格 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[buy_price]", old("dtb_item[buy_price]", isset($data['buy_price']) ? $data['buy_price'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
            {!! $errors->first("dtb_item[buy_price]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[buy_price]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[profit]">利益</label>
            {!! Form::text("dtb_item[profit]", old("dtb_item[profit]", isset($data['profit']) ? $data['profit'] : ''), ['class' => 'form-control', 'readonly' => true,'id' => 'profit']) !!}
            {!! $errors->first("dtb_item[profit]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[profit]") .'</p>
            ' : ''!!}
        </div>
    </div>
</div>