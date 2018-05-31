@extends('layouts.default')
@section('title')
@lang('view.post_product')
@endsection
@section('head')
<link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{ asset('css/product/post.css') }}">
<link href="{{asset('lib/jquery-upload/css/jquery.fileuploader-theme-thumbnails.css')}}" type="text/css" rel="stylesheet"/>
<link href="{{asset('lib/jquery-upload/css/jquery.fileuploader.min.css')}}" type="text/css" rel="stylesheet"/>
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
                        <form class="form-inline text-center" id="filter-post" role="form" method="GET">
                            {!! Form::text('item_id', !empty($data['item_id']) ? $data['item_id'] : '192375777401', ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'item_id']) !!}
                            <button class="btn btn-primary" type="button" id="btn-get-item-ebay-info"><i class="fa fa-info-circle"></i> {{ __('view.filter') }}</button>
                            @php
                                $typeCheck = 1;
                                if (!empty($data['dtb_item']['type'])) {
                                    $typeCheck = $data['dtb_item']['type'];
                                }
                            @endphp
                            @foreach($originType as $key => $type)
                            &emsp;&emsp;&emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" {{$key == $typeCheck ? 'checked' :''}} value="{{$key}}">
                                {{$type}}
                            </label>
                            @endforeach
                            {{-- B00RF2ZNI0 --}}
                            {{-- s583357763 --}}
                            &emsp;&emsp;&emsp;&emsp;{!! Form::text('id_ebay_or_amazon', !empty($data['original_id']) ? $data['original_id'] : 'B01GUPMJMA', ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'id_ebay_or_amazon']) !!}
                            <button class="btn btn-primary" type="button" id="btn-get-yahoo-or-amazon"><i class="fa fa-info-circle"></i> {{ __('view.image_acquisition') }}</button>
                        </form>
                        <p class="text-danger display-none" id="item-ebay-invalid">@lang('view.item_not_found')</p>
                        <form role="form" id="form-post" enctype="multipart/form-data" method="post" action="{{route('admin.product.post-product-confirm')}}">
                            @csrf
                            <div id="conten-ajax">
                                <div class="ebay-info">
                                    @if(!empty($data))
                                        @include('admin.product.component.item_ebay_info')
                                    @endif
                                </div>
                                <div class="yahoo-or-amazon-info">
                                    @if(!empty($data))
                                        @include('admin.product.component.item_yahoo_or_amazon_info')
                                    @endif
                                </div>
                                <div class="{{!empty($data) ? '' : 'display-none'}} margin-20" id="profit-calculation">
                                    <button type="button" class="btn btn-primary" id="btn-calculator-profit"><i class="fa fa-calculator fa-fw"></i> @lang('view.product_submission')</button>
                                </div>
                                <div class="calculator-info">
                                    @if(!empty($data))
                                        @include('admin.product.component.calculator_info')
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer {{!empty($data) ? '' : 'display-none'}}" id="post-product">
                        <div class="form-group text-center col-sm-4 col-sm-offset-4">
                            <button type="button" class="btn btn-block btn-primary btn-lg" id="save"><i class="fa fa-floppy-o fa-fw"></i> @lang('view.benefit_calculation')</button>
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
<script src="https://images-fe.ssl-images-amazon.com/images/G/09/mwsportal/scratchpad/scratchpad.lib.3party._CB181116593_.js"></script>
<script src="{{ asset('js/post-product.js') }}"></script>
<script>
    var urlGetItemEbayInfo = "{{ route('admin.product.api-get-item-ebay-info') }}";
    var urlGetItemYahooOrAmazonInfo = "{{ route('admin.product.api-get-item-yahoo-or-amazon-info') }}";
    var urlCalculatorProfit = "{{ route('admin.product.calculator-profit') }}";
    var urlPostProductConfirm = "{{ route('admin.product.post-product-confirm') }}";
    var updateProfit = "{{ route('admin.product.update-profit') }}";
    // $('input[type="radio"].minimal').iCheck({
        // radioClass: 'iradio_minimal-blue'
    // });
    var numberSpecificItem = '{{ !empty($data['dtb_item_specifics']) ? count($data['dtb_item_specifics']) : 0 }}';
    var urlGetImageInit = "{{route('admin.product.get-image-init')}}";
</script>
@endsection
