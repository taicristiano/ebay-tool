@extends('layouts.default')
@section('head')
<link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{ asset('css/product/post.css') }}">
<link href="{{asset('lib/jQueryFiler/css/jquery.filer.css')}}" type="text/css" rel="stylesheet"/>
<link href="{{asset('lib/jQueryFiler/css/themes/jquery.filer-dragdropbox-theme.css')}}" type="text/css" rel="stylesheet"/>
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
                            <button class="btn btn-primary" type="button" id="btn-get-item-ebay-info"><i class="fa fa-info-circle"></i> {{ __('view.filter') }}</button>
                            &emsp;&emsp;&emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" checked value="yahoo_auction">
                                @lang('view.yahoo_auction')
                            </label>
                            &emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" value="amazon">
                                @lang('view.amazon')
                            </label>
                            &emsp;&emsp;&emsp;&emsp;{!! Form::text('id_ebay_or_amazon', 'c642534441', ['class' => 'form-control', 'placeholder' => __('view.itemID'), 'id' => 'id_ebay_or_amazon']) !!}
                            <button class="btn btn-primary" type="button" id="btn-get-ebay-or-amazon"><i class="fa fa-info-circle"></i> {{ __('view.image_acquisition') }}</button>
                        </form>
                        <p class="text-danger display-none" id="item-ebay-invalid">Item not found</p>
                    </div>
                </div>
                <form role="form" id="form-post">
                    @csrf
                    <div id="conten-ajax">
                    </div>
                </form>
            </div>
        </div>
    </section>
    @endsection
</div>
@section('script')
<script src="{{asset('lib/jQueryFiler/js/jquery.filer.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('js/post-product.js') }}"></script>
<script>
    var urlGetItemEbayInfo = "{{ route('admin.product.api-get-item-ebay-info') }}";
    var urlGetItemYahooOrAmazonInfo = "{{ route('admin.product.api-get-item-yahoo-or-amazon-info') }}";
    var urlPosProduct = "{{ route('admin.product.post-product') }}";
    $('input[type="radio"].minimal').iCheck({
        radioClass: 'iradio_minimal-blue'
    });
</script>
@endsection