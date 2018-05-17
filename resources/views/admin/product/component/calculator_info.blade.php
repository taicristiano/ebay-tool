<div class="box box-success" id="item-calculator-info">
    <div class="box-header with-border">■製品詳細</div>
    <div class="box-body">
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">商品サイズ <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="specific-itemssss">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="dtb_item[item_name]">商品重量 <span class="text-danger">(*)</span></label>
                    {!! Form::text("dtb_item_specifics[1][name]", old("dtb_item_specifics[1][name]", isset($data['dtb_item_specifics'][1]['name']) ? $data['dtb_item_specifics'][1]['name'] : ''), ['class' => 'specific-name form-control']) !!}
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                    <label for="dtb_item[item_name]">梱包資材量 <span class="text-danger">(*)</span></label>
                <div class="form-group input-group">
                    {!! Form::text("dtb_item_specifics[1][value]", old("dtb_item_specifics[1][value]", isset($data['dtb_item_specifics'][1]['value']) ? $data['dtb_item_specifics'][1]['value'] : ''), ['class' => 'specific-value form-control']) !!}
                    <span class="input-group-addon">g</span>
                </div>
            </div>
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[duration]">発送方法 <span class="text-danger">(*)</span></label>
            {!! Form::select("dtb_item[duration]", [1,3], old("dtb_item[duration]", isset($data['duration']['value']) ? $data['duration']['value'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[duration]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[duration]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">送料 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">販売手数料 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">paypal手数料 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">仕入れ元価格 <span class="text-danger">(*)</span></label>
            {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
            {!! $errors->first("dtb_item[item_name]") ? '
            <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
            ' : ''!!}
        </div>
        <div class="form-group form-group-custom">
            <label for="dtb_item[item_name]">利益: 1234Yen</label>
        </div>
    </div>
</div>