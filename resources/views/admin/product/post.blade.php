@extends('layouts.default')
@section('title')
@lang('view.post_product')
@endsection
@section('head')
<link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{ asset('css/product/post.css') }}">
<link href="{{asset('lib/jquery-upload/css/jquery.fileuploader-theme-thumbnails.css')}}" type="text/css" rel="stylesheet"/>
<link href="{{asset('lib/jquery-upload/css/jquery.fileuploader.min.css')}}" type="text/css" rel="stylesheet"/>
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/select2.min.css') }}">
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.post_product')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">@lang('view.post_product')</div>
                    <div class="box-body">
                        <form role="form">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-md-8">
                                            {!! Form::text('item_id', !empty($data['item_id']) ? $data['item_id'] : '192375777401', ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'item_id']) !!}
                                            <p class="text-danger display-none invalid" id="item-ebay-invalid">@lang('view.item_not_found')</p>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <button class="btn btn-primary" type="button" id="btn-get-item-ebay-info"><i class="fa fa-info-circle"></i> {{ __('view.filter') }}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    @php
                                        $typeCheck = 1;
                                        if (!empty($data['dtb_item']['type'])) {
                                            $typeCheck = $data['dtb_item']['type'];
                                        }
                                    @endphp
                                    <div class="form-group">
                                        <div class="col-md-1">
                                        </div>
                                        @foreach($originType as $key => $type)
                                        <div class="col-xs-6 col-md-5">
                                            <input type="radio" name="type" class="minimal type" {{$key == $typeCheck ? 'checked' :''}} value="{{$key}}">
                                            {{$type}}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- B00RF2ZNI0 --}}
                                {{-- s583357763 --}}
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-md-8">
                                            {!! Form::text('id_ebay_or_amazon', !empty($data['original_id']) ? $data['original_id'] : 'B01GUPMJMA', ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'id_ebay_or_amazon']) !!}
                                            <p class="text-danger invalid" id="item-yahoo-or-amazon-invalid"></p>
                                        </div>
                                        <div class="col-xs-12 col-md-4">
                                            <button class="btn btn-primary" type="button" id="btn-get-yahoo-or-amazon"><i class="fa fa-info-circle"></i> {{ __('view.image_acquisition') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form role="form" id="form-post" enctype="multipart/form-data" method="post" action="{{route('admin.product.post-product-confirm')}}">
                            @csrf
                            <div id="conten-ajax">
                                <div class="ebay-info">
                                    @include('admin.product.component.item_ebay_info')
                                </div>
                                <div class="yahoo-or-amazon-info">
                                    @include('admin.product.component.item_yahoo_or_amazon_info')
                                </div>
                                <div class="{{!empty($data) ? '' : 'display-none'}} margin-20" id="profit-calculation">
                                    <button type="button" class="btn btn-primary" id="btn-calculator-profit"><i class="fa fa-calculator fa-fw"></i> @lang('view.benefit_calculation')</button>
                                </div>
                                <div class="calculator-info">
                                    @include('admin.product.component.calculator_info')
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer {{!empty($data) ? '' : 'display-none'}}" id="post-product">
                        <div class="form-group text-center col-sm-4 col-sm-offset-4">
                            <button type="button" class="btn btn-block btn-primary btn-lg" id="save"><i class="fa fa-floppy-o fa-fw"></i> @lang('view.product_submission')</button>
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
<script src="{{ asset('adminlte/plugins/select2/select2.min.js') }}"></script>
<script>
    var urlGetItemEbayInfo          = "{{ route('admin.product.api-get-item-ebay-info') }}";
    var urlGetItemYahooOrAmazonInfo = "{{ route('admin.product.api-get-item-yahoo-or-amazon-info') }}";
    var urlCalculatorProfit         = "{{ route('admin.product.calculator-profit') }}";
    var urlPostProductConfirm       = "{{ route('admin.product.post-product-confirm') }}";
    var numberSpecificItem          = '{{ !empty($data['dtb_item_specifics']) ? count($data['dtb_item_specifics']) : 1 }}';
    var urlGetImageInit             = "{{route('admin.product.get-image-init')}}";
    var urlSearchCategory           = "{{route('admin.product.search-category')}}";
    // $('input[type="radio"].minimal').iCheck({
        // radioClass: 'iradio_minimal-blue'
    // });
</script>
@endsection
