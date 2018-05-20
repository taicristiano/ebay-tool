@extends('layouts.default')
@section('head')
<link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{ asset('css/product/post.css') }}">
<link href="{{asset('lib/jquery-upload/css/jquery.fileuploader-theme-thumbnails.css')}}" type="text/css" rel="stylesheet"/>
<link href="{{asset('lib/jquery-upload/css/jquery.fileuploader.min.css')}}" type="text/css" rel="stylesheet"/>
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
                        <form class="form-inline text-center" id="filter-post" role="form" method="GET">
                            {!! Form::text('item_id', 192375777401, ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'item_id']) !!}
                            <button class="btn btn-primary" type="button" id="btn-get-item-ebay-info"><i class="fa fa-info-circle"></i> {{ __('view.filter') }}</button>
                            @foreach($originType as $key => $type)
                            &emsp;&emsp;&emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" {{$key == 1 ? 'checked' :''}} value="{{$key}}">
                                {{$type}}
                            </label>
                            @endforeach
                            <!-- p607601748 -->
                            <!-- c642534441 -->
                            <!-- r245539002 -->
                            <!-- u199058848 -->
                            &emsp;&emsp;&emsp;&emsp;{!! Form::text('id_ebay_or_amazon', 'u199058848', ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'id_ebay_or_amazon']) !!}
                            <button class="btn btn-primary" type="button" id="btn-get-yahoo-or-amazon"><i class="fa fa-info-circle"></i> {{ __('view.image_acquisition') }}</button>
                        </form>
                        <p class="text-danger display-none" id="item-ebay-invalid">Item not found</p>
                        <form role="form" id="form-post" enctype="multipart/form-data" method="post" action="{{route('admin.product.post-product')}}">
                            @csrf
                            <div id="conten-ajax">
                                <div class="ebay-info">
                                    <div class="box box-success" id="item-ebay-content">
                                        <div class="box-header with-border">■製品詳細</div>
                                        <div class="box-body">
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[item_name]">商品名 <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control']) !!}
                                                {!! $errors->first("dtb_item[item_name]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                {!! Form::hidden("dtb_item[category_id]", isset($data['dtb_item']['category_id']) ? $data['dtb_item']['category_id'] : '', ['id' => 'category_id']) !!}
                                                <label for="dtb_item[category_name]">カテゴリー <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[category_name]", old("dtb_item['category_name']", isset($data['dtb_item']['category_name']) ? $data['dtb_item']['category_name'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                                {!! $errors->first("dtb_item[category_name]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[category_name]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[item_name]">JAN/UPC <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[item_name]", old("dtb_item[item_name]", isset($data['dtb_item']['item_name']) ? $data['dtb_item']['item_name'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                                {!! $errors->first("dtb_item[item_name]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                {!! Form::hidden("dtb_item[condition_id]", isset($data['dtb_item']['condition_id']) ? $data['dtb_item']['condition_id'] : '') !!}
                                                <label for="dtb_item[condition_name]">商品名 <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[condition_name]", old("dtb_item[condition_name]", isset($data['dtb_item']['condition_name']) ? $data['dtb_item']['condition_name'] : ''), ['class' => 'form-control']) !!}
                                                {!! $errors->first("dtb_item[condition_name]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[condition_name]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <p>■仕様詳細 plici</p>
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
                                            <p>■販売詳細</p>
                                            <hr>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[price]">販売価格 <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[price]", old("dtb_item[price]", isset($data['dtb_item']['price']) ? $data['dtb_item']['price'] : ''), ['class' => 'form-control', 'id' => 'sell_price']) !!}
                                                {!! $errors->first("dtb_item[price]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[price]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <p>■設定価値</p>
                                            <hr>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[duration]">販売期間 <span class="text-danger">(*)</span></label>
                                                {!! Form::select("dtb_item[duration]", $data['duration']['option'], old("dtb_item[duration]", isset($data['duration']['value']) ? $data['duration']['value'] : ''), ['class' => 'form-control']) !!}
                                                {!! $errors->first("dtb_item[duration]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[duration]") .'</p>
                                                ' : ''!!}
                                            </div>

                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[quantity]">個数 <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[quantity]", old("dtb_setting[quantity]", isset($data['dtb_setting']['quantity']) ? $data['dtb_setting']['quantity'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                                {!! $errors->first("dtb_item[quantity]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[quantity]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[shipping_policy_id]">Shippingポリシー <span class="text-danger">(*)</span></label>
                                                {!! Form::select("dtb_item[shipping_policy_id]", $data['dtb_setting_policies']['shipping'], old("dtb_item[shipping_policy_id]", isset($data['dtb_setting_policies']['shipping']) ? $data['dtb_setting_policies']['shipping'] : ''), ['class' => 'form-control']) !!}
                                                {!! $errors->first("dtb_item[shipping_policy_id]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[shipping_policy_id]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[payment_policy_id]">Paymentポリシー <span class="text-danger">(*)</span></label>
                                                {!! Form::select("dtb_item[payment_policy_id]", $data['dtb_setting_policies']['payment'], old("dtb_item[payment_policy_id]", isset($data['dtb_setting_policies']['payment']) ? $data['dtb_setting_policies']['payment'] : ''), ['class' => 'form-control']) !!}
                                                {!! $errors->first("dtb_item[payment_policy_id]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[payment_policy_id]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[return_policy_id]">Returnポリシー <span class="text-danger">(*)</span></label>
                                                {!! Form::select("dtb_item[return_policy_id]", $data['dtb_setting_policies']['return'], old("dtb_item[return_policy_id]", isset($data['dtb_setting_policies']['return']) ? $data['dtb_setting_policies']['return'] : ''), ['class' => 'form-control']) !!}
                                                {!! $errors->first("dtb_item[return_policy_id]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[return_policy_id]") .'</p>
                                                ' : ''!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="yahoo-or-amazon-info">
                                    <div class="box box-success" id="item-yaohoo-or-amazon-content">
                                        <div class="box-header with-border">■製品詳細</div>
                                        <div class="box-body">
                                            @if($price)
                                            <p>即決価格: <span id="buy_price">{{ $price }}</span></p>
                                            @endif
                                            <input type="file" name="files" id="files">
                                        </div>
                                        <span id="product_size" class="display-none">{{ isset($data['product_size']) ? $data['product_size'] : '' }}</span>
                                        <span id="commodity_weight" class="display-none">{{ isset($data['commodity_weight']) ? $data['commodity_weight'] : '' }}</span>
                                        <span id="length" class="display-none">{{ isset($data['length']) ? $data['length'] : '' }}</span>
                                        <span id="height" class="display-none">{{ isset($data['height']) ? $data['height'] : '' }}</span>
                                        <span id="width" class="display-none">{{ isset($data['width']) ? $data['width'] : '' }}</span>
                                    </div>
                                </div>
                                <div class="display-none margin-20" id="profit-calculation">
                                    <button type="button" class="btn btn-primary" id="btn-calculator-profit"><i class="fa fa-calculator fa-fw"></i> 商品投稿</button>
                                </div>
                                <div class="calculator-info">
                                    <div class="box box-success" id="item-calculator-info">
                                        <div class="box-header with-border">■製品詳細</div>
                                        <div class="box-body">
                                            @if($data['istTypeAmazon'])
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[item_name]">商品サイズ <span class="text-danger">(*)</span></label>
                                                {!! Form::text("data_amazon[product_size]", old("data_amazon[product_size]", isset($data['data_amazon']['product_size']) ? $data['data_amazon']['product_size'] : ''), ['class' => 'form-control product_size']) !!}
                                                {!! $errors->first("data_amazon[product_size]") ? '
                                                <p class="text-danger">'. $errors->first("data_amazon[product_size]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="specific-itemssss">
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="dtb_item[item_name]">商品重量 <span class="text-danger">(*)</span></label>
                                                        {!! Form::text("data_amazon[name]", old("data_amazon[commodity_weight]", isset($data['data_amazon']['commodity_weight']) ? $data['data_amazon']['commodity_weight'] : ''), ['class' => 'specific-name form-control commodity_weight', 'readonly' => true]) !!}
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
                                                {!! Form::select("dtb_item[duration]", $data['setting_shipping_option'], old("dtb_item[duration]", isset($data['duration']['value']) ? $data['duration']['value'] : ''), ['class' => 'form-control']) !!}
                                                {!! $errors->first("dtb_item[duration]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[duration]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[ship_fee]">送料 <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[ship_fee]", old("dtb_item[ship_fee]", isset($data['ship_fee']) ? $data['ship_fee'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                                {!! $errors->first("dtb_item[item_name]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[item_name]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            @endif
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[ebay_fee]">販売手数料 <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[ebay_fee]", old("dtb_item[ebay_fee]", isset($data['ebay_fee']) ? $data['ebay_fee'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                                {!! $errors->first("dtb_item[ebay_fee]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[ebay_fee]") .'</p>
                                                ' : ''!!}
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label for="dtb_item[paypal_fee]">paypal手数料 <span class="text-danger">(*)</span></label>
                                                {!! Form::text("dtb_item[paypal_fee]", old("dtb_item[paypal_fee]", isset($data['paypal_fee']) ? $data['paypal_fee'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
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
                                                {!! Form::text("dtb_item[profit]", old("dtb_item[profit]", isset($data['profit']) ? $data['profit'] : ''), ['class' => 'form-control', 'readonly' => true]) !!}
                                                {!! $errors->first("dtb_item[profit]") ? '
                                                <p class="text-danger">'. $errors->first("dtb_item[profit]") .'</p>
                                                ' : ''!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer display-none" id="post-product">
                        <div class="text-center margin-20 text-center">
                            <button type="button" class="btn btn-primary" id="save"><i class="fa fa-floppy-o fa-fw"></i> 利益計算</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal modal-loading"></div>
    @endsection
</div>
@section('script')
<script src="{{asset('lib/jquery-upload/js/jquery.fileuploader.js')}}"></script>
<script src="{{asset('lib/jquery-upload/js/custom.js')}}"></script>
<script src="{{asset('adminlte/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('js/post-product.js') }}"></script>
<script>
    var urlGetItemEbayInfo = "{{ route('admin.product.api-get-item-ebay-info') }}";
    var urlGetItemYahooOrAmazonInfo = "{{ route('admin.product.api-get-item-yahoo-or-amazon-info') }}";
    var urlCalculatorProfit = "{{ route('admin.product.calculator-profit') }}";
    var urlPosProduct = "{{ route('admin.product.post-product') }}";
    $('input[type="radio"].minimal').iCheck({
        radioClass: 'iradio_minimal-blue'
    });
</script>
@endsection