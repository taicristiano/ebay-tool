<div class="box box-success">
    <div class="box-header with-border">■製品詳細</div>
    <form role="form">
        <div class="box-body">
            <div class="form-group">
                <label for="quantity">商品名 <span class="text-danger">(*)</span></label>
                {!! Form::text('quantity', old('quantity', isset($data['item_name']) ? $data['item_name'] : ''), ['class' => 'form-control']) !!}
                {!! $errors->first('quantity') ? '
                <p class="text-danger">'. $errors->first('quantity') .'</p>
                ' : ''!!}
            </div>
            <div class="form-group">
                <label for="quantity">カテゴリー <span class="text-danger">(*)</span></label>
                {!! Form::text('quantity', old('quantity', isset($data['item_name']) ? $data['item_name'] : ''), ['class' => 'form-control']) !!}
                {!! $errors->first('quantity') ? '
                <p class="text-danger">'. $errors->first('quantity') .'</p>
                ' : ''!!}
            </div>
            <div class="form-group">
                <label for="quantity">JAN/UPC <span class="text-danger">(*)</span></label>
                {!! Form::text('quantity', old('quantity', isset($data['item_name']) ? $data['item_name'] : ''), ['class' => 'form-control']) !!}
                {!! $errors->first('quantity') ? '
                <p class="text-danger">'. $errors->first('quantity') .'</p>
                ' : ''!!}
            </div>
            <div class="form-group">
                <label for="quantity">商品状態 <span class="text-danger">(*)</span></label>
                {!! Form::text('quantity', old('quantity', isset($data['item_name']) ? $data['item_name'] : ''), ['class' => 'form-control']) !!}
                {!! $errors->first('quantity') ? '
                <p class="text-danger">'. $errors->first('quantity') .'</p>
                ' : ''!!}
            </div>
            <p>■販売詳細</p>
            <hr>
            <div class="form-group">
                <label for="quantity">販売価格 <span class="text-danger">(*)</span></label>
                {!! Form::text('quantity', old('quantity', isset($data['item_name']) ? $data['item_name'] : ''), ['class' => 'form-control']) !!}
                {!! $errors->first('quantity') ? '
                <p class="text-danger">'. $errors->first('quantity') .'</p>
                ' : ''!!}
            </div>
            <p>■設定価値</p>
            <hr>
            <div class="form-group">
                <label for="quantity">販売価格 <span class="text-danger">(*)</span></label>
                {!! Form::text('quantity', old('quantity', isset($data['item_name']) ? $data['item_name'] : ''), ['class' => 'form-control']) !!}
                {!! $errors->first('quantity') ? '
                <p class="text-danger">'. $errors->first('quantity') .'</p>
                ' : ''!!}
            </div>
            <div class="form-group">
                <label for="quantity">販売価格 <span class="text-danger">(*)</span></label>
                {!! Form::text('quantity', old('quantity', isset($data['item_name']) ? $data['item_name'] : ''), ['class' => 'form-control']) !!}
                {!! $errors->first('quantity') ? '
                <p class="text-danger">'. $errors->first('quantity') .'</p>
                ' : ''!!}
            </div>
            <div class="form-group">
                <label for="store_id">Shippingポリシー <span class="text-danger">(*)</span></label>
                {!! Form::select("store_id", ['first', 'second'], old('store_id', isset($setting->store_id) ? $setting->store_id : ''), ['class' => 'form-control', 'id' => 'store_id']) !!}
                {!! $errors->first('store_id') ? '
                <p class="text-danger">'. $errors->first('store_id') .'</p>
                ' : ''!!}
            </div>
            <div class="form-group">
                <label for="store_id">Paymentポリシー <span class="text-danger">(*)</span></label>
                {!! Form::select("store_id", ['first', 'second'], old('store_id', isset($setting->store_id) ? $setting->store_id : ''), ['class' => 'form-control', 'id' => 'store_id']) !!}
                {!! $errors->first('store_id') ? '
                <p class="text-danger">'. $errors->first('store_id') .'</p>
                ' : ''!!}
            </div>
            <div class="form-group">
                <label for="store_id">Returnポリシー <span class="text-danger">(*)</span></label>
                {!! Form::select("store_id", ['first', 'second'], old('store_id', isset($setting->store_id) ? $setting->store_id : ''), ['class' => 'form-control', 'id' => 'store_id']) !!}
                {!! $errors->first('store_id') ? '
                <p class="text-danger">'. $errors->first('store_id') .'</p>
                ' : ''!!}
            </div>
        </div>
    </form>
</div>