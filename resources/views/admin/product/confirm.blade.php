@extends('layouts.default')
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
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="box box-success">
                    <form class="form-horizontal">
                        <div class="box-header with-border"@lang('view.post_product')</div>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.item_id')</label>
                                    <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['item_id']}}</label>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.original_id')</label>
                                    <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['original_id']}}</label>
                                </div>
                            </div>
                            <div id="conten-ajax">
                                <div class="ebay-info">
                                    <div class="box box-success" id="item-ebay-content">
                                        <div class="box-header with-border">@lang('view.product_details')</div>
                                        <div class="box-body">
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.product_name')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['item_name']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.category')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['category_name']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.JAN/UPC')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['item_name']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.condition_name')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['condition_name']}}</label>
                                            </div>
                                            <p>@lang('view.specifications')</p>
                                            <hr>
                                            @foreach($data['dtb_item_specifics'] as $key => $value)
                                            <div class="form-group">
                                                <label class="col-md-6 col-sm-6 col-xs-6">{{$data['dtb_item_specifics'][$key]['name']}}</label>
                                                <label class="col-md-6 col-sm-6 col-xs-6">{{$data['dtb_item_specifics'][$key]['value']}}</label>
                                            </div>
                                            @endforeach
                                            <p>@lang('view.sale_details')</p>
                                            <hr>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.sell_price')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['price']}}</label>
                                            </div>
                                            <p>@lang('view.setting_value')</p>
                                            <hr>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.sale_period')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['duration']}}</label>
                                            </div>

                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.quantity')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['quantity']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.shipping_policy')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['shipping_policy_id']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.payment_policy')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['payment_policy_id']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.return_policy')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{isset($data['dtb_item']['return_policy_id']) ? 'return_policy_id' : ''}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="yahoo-or-amazon-info">
                                    <div class="box box-success" id="item-yaohoo-or-amazon-content">
                                        <div class="box-header with-border">@lang('view.product_details')</div>
                                        <div class="box-body">
                                            @if(isset($data['dtb_item']['buy_price']))
                                            <p>@lang('view.buy_price'): <span id="buy_price">{{$data['dtb_item']['buy_price']}}</span></p>
                                            @endif
                                        </div>
                                        <div class="fileuploader fileuploader-theme-thumbnails">
                                            <div class="fileuploader-items">
                                                <ul class="fileuploader-items-list">
                                                    @for($i = 0; $i < $data['number_file']; $i++)
                                                    <li class="fileuploader-item file-type-image file-ext-no" style="width: 115px">
                                                        <div class="fileuploader-item-inner">
                                                            <div class="thumbnail-holder">
                                                                <div class="fileuploader-item-image">
                                                                    <img src="{{$data['url_preview_' . $i]}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="calculator-info">
                                    <div class="box box-success" id="item-calculator-info">
                                        <div class="box-header with-border">@lang('view.product_details')</div>
                                        <div class="box-body">
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.product_size')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['product_size']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.commodity_weight')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['commodity_weight']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.material_quantity')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['material_quantity']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.duration')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['duration']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.ship_fee')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['ship_fee']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.ebay_fee')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['ebay_fee']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.paypal_fee')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['paypal_fee']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.buy_price')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['buy_price']}}</label>
                                            </div>
                                            <div class="form-group form-group-custom">
                                                <label class="col-md-3 col-sm-3 col-xs-3">@lang('view.profit')</label>
                                                <label class="col-md-9 col-sm-9 col-xs-9">{{$data['dtb_item']['profit']}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer" id="post-product">
                            <div class="text-center margin-20 text-center">
                                <button type="button" class="btn btn-primary" id="save"><i class="fa fa-floppy-o fa-fw"></i> @lang('view.benefit_calculation')</button>
                            </div>
                        </div>
                    </form>
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
    $('input[type="radio"].minimal').iCheck({
        radioClass: 'iradio_minimal-blue'
    });
    var urlGetImageInit = "{{route('admin.product.get-image-init')}}";
</script>
@endsection
