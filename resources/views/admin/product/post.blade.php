@extends('layouts.default')
@section('head')
<link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/all.css')}}">
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => 'Post product'])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="box box-success">
                    <div class="box-header with-border">Post product</div>
                    <div class="box-body">
                        <form class="form-inline" id="filter-post" role="form" method="GET">
                            {!! Form::text('item_id', 192375777401, ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'item_id']) !!}
                            <button class="btn btn-primary" type="button" id="get-item-ebay-info"><i class="fa fa-info-circle"></i> {{ __('view.filter') }}</button>
                            &emsp;&emsp;&emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" checked value="1">
                                @lang('view.yahoo_auction')
                            </label>
                            &emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" value="2">
                                @lang('view.amazon')
                            </label>
                            &emsp;&emsp;&emsp;&emsp;{!! Form::text('id_ebay_or_amazon', old('id_ebay_or_amazon'), ['class' => 'form-control', 'placeholder' => __('view.itemID')]) !!}
                            <button class="btn btn-primary"><i class="fa fa-info-circle"></i> {{ __('view.image_acquisition') }}</button>
                        </form>
                        <p class="text-danger display-none" id="item-ebay-invalid">Item not found</p>
                    </div>
                </div>
                <div id="conten-ajax">
                    <div class="box box-success">
                        <div class="box-header with-border">■製品詳細</div>
                        <form role="form">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="dtb_item['item_name']">商品名 <span class="text-danger">(*)</span></label>
                                    {!! Form::text("dtb_item['item_name']", old("dtb_item['item_name']", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
                                    {!! $errors->first("dtb_item['item_name']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['item_name']") .'</p>
                                    ' : ''!!}
                                </div>
                                <div class="form-group">
                                    {!! Form::hidden("dtb_item['category_id']", isset($data['dtb_item']['category_id']) ? $data['dtb_item']['category_id'] : '') !!}
                                    <label for="dtb_item['category_name']">商品名 <span class="text-danger">(*)</span></label>
                                    {!! Form::text("dtb_item['category_name']", old("dtb_item['category_name']", isset($data['dtb_item']['category_name']) ? $data['dtb_item']['category_name'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                    {!! $errors->first("dtb_item['category_name']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['category_name']") .'</p>
                                    ' : ''!!}
                                </div>
                                <div class="form-group">
                                    <label for="dtb_item['item_name']">JAN/UPC <span class="text-danger">(*)</span></label>
                                    {!! Form::text("dtb_item['item_name']", old("dtb_item['item_name']", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                    {!! $errors->first("dtb_item['item_name']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['item_name']") .'</p>
                                    ' : ''!!}
                                </div>
                                <div class="form-group">
                                    {!! Form::hidden("dtb_item['condition_id']", isset($data['dtb_item']['condition_id']) ? $data['dtb_item']['condition_id'] : '') !!}
                                    <label for="dtb_item['condition_name']">商品名 <span class="text-danger">(*)</span></label>
                                    {!! Form::text("dtb_item['condition_name']", old("dtb_item['condition_name']", isset($data['dtb_item']['condition_name']) ? $data['dtb_item']['condition_name'] : ''), ['class' => 'form-control']) !!}
                                    {!! $errors->first("dtb_item['condition_name']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['condition_name']") .'</p>
                                    ' : ''!!}
                                </div>
                                <p>■販売詳細</p>
                                <hr>
                                <div class="form-group">
                                    <label for="dtb_item['price']">販売価格 <span class="text-danger">(*)</span></label>
                                    {!! Form::text("dtb_item['price']", old("dtb_item['price']", isset($data['dtb_item']['price']) ? $data['dtb_item']['price'] : ''), ['class' => 'form-control']) !!}
                                    {!! $errors->first("dtb_item['price']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['price']") .'</p>
                                    ' : ''!!}
                                </div>
                                <p>■設定価値</p>
                                <hr>
                                <div class="form-group">
                                    <label for="dtb_item['duration']">販売期間 <span class="text-danger">(*)</span></label>
                                    {!! Form::text("dtb_item['duration']", old("dtb_setting['duration']", isset($data['dtb_setting']['duration']) ? $data['dtb_setting']['duration'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                    {!! $errors->first("dtb_item['duration']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['duration']") .'</p>
                                    ' : ''!!}
                                </div>
                                <div class="form-group">
                                    <label for="dtb_item['quantity']">個数 <span class="text-danger">(*)</span></label>
                                    {!! Form::text("dtb_item['quantity']", old("dtb_setting['quantity']", isset($data['dtb_setting']['quantity']) ? $data['dtb_setting']['quantity'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                    {!! $errors->first("dtb_item['quantity']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['quantity']") .'</p>
                                    ' : ''!!}
                                </div>
                                <div class="form-group">
                                    <label for="dtb_item['shipping_policy_id']">Shippingポリシー <span class="text-danger">(*)</span></label>
                                    {!! Form::select("dtb_item['shipping_policy_id']", ['first', 'second'], old("dtb_item['shipping_policy_id']", isset($data['dtb_setting_policies']['type_1']) ? $data['dtb_setting_policies']['type_1'] : ''), ['class' => 'form-control']) !!}
                                    {!! $errors->first("dtb_item['shipping_policy_id']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['shipping_policy_id']") .'</p>
                                    ' : ''!!}
                                </div>
                                <div class="form-group">
                                    <label for="dtb_item['payment_policy_id']">Paymentポリシー <span class="text-danger">(*)</span></label>
                                    {!! Form::select("dtb_item['payment_policy_id']", ['first', 'second'], old("dtb_item['payment_policy_id']", isset($data['dtb_setting_policies']['type_2']) ? $data['dtb_setting_policies']['type_2'] : ''), ['class' => 'form-control']) !!}
                                    {!! $errors->first("dtb_item['payment_policy_id']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['payment_policy_id']") .'</p>
                                    ' : ''!!}
                                </div>
                                <div class="form-group">
                                    <label for="dtb_item['return_policy_id']">Returnポリシー <span class="text-danger">(*)</span></label>
                                    {!! Form::select("dtb_item['return_policy_id']", ['first', 'second'], old("dtb_item['return_policy_id']", isset($data['dtb_setting_policies']['type_3']) ? $data['dtb_setting_policies']['type_3'] : ''), ['class' => 'form-control']) !!}
                                    {!! $errors->first("dtb_item['return_policy_id']") ? '
                                    <p class="text-danger">'. $errors->first("dtb_item['return_policy_id']") .'</p>
                                    ' : ''!!}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
</div>
@section('script')
<script src="{{asset('adminlte/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('js/post-product.js') }}"></script>
<script>
    var urlGetItemEbayInfo = "{{ route('admin.product.api-get-item-ebay-info') }}";
    $('input[type="radio"].minimal').iCheck({
        radioClass: 'iradio_minimal-blue'
    });
</script>
@endsection