@extends('layouts.default')
@section('title')
@lang('view.product_list')
@endsection
@section('content')
    <div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.product_list')])
    <section class="content">
        <div class="row">
            @include('layouts.component.alert')
            <div class="col-xs-12 col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">@lang('view.product_list') <span class="pull-right padding-right-15">{{ $exchangeRate->rate }}@lang('view.man_to_usd')</span></div>
                    <div class="box-body">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <form class="form-inline" id="filter-post" role="form" method="GET">
                                <label for="search">@lang('view.search')</label>
                                {!! Form::text('search', old('search'), ['class' => 'form-control']) !!}
                                <button class="btn btn-primary"><i class="fa fa-search"></i> {{ __('view.filter') }}</button>
                                <button type="button" class="btn btn-warning" id="end-item-btn"><i class="fa fa-times"></i> {{ __('view.withdrawal_of_exhibition') }}</button>
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <form class="form-inline pull-right" id="export-csv" role="form" method="GET">
                                <button class="btn btn-primary" id="csv-product"><i class="fa fa-download"></i> {{ __('view.csv_product') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-align-center table-responsive" id="list-product-table">
                            <thead>
                                <tr>
                                    <th>
                                        {{-- <input type="checkbox" id="end-item-all"> --}}
                                    </th>
                                    <th>{{ __('view.created_at') }}</th>
                                    <th>{{ __('view.image') }}</th>
                                    <th>{{ __('view.product_info') }}</th>
                                    <th>{{ __('view.quantity') }}</th>
                                    <th>{{ __('view.sell_price') }}</th>
                                    <th>{{ __('view.original_type') }}</th>
                                    <th>{{ __('view.buy_price') }}</th>
                                    <th>{{ __('view.ship_fee') }}</th>
                                    <th>{{ __('view.temp_profit') }}</th>
                                    <th>{{ __('view.min_max') }}</th>
                                    <th>{{ __('view.monitor_type') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!isset($products) || !count($products))
                                <tr>
                                    <td colspan="9" align="center">{{ __('message.no_data') }}</td>
                                </tr>
                                @else
                                @php
                                    $urlEbayKeywordSearchtTemplate = 'https://www.ebay.com/sch/i.html?_from=R40&_nkw=KEYWORD&_in_kw=1&_ex_kw=&_sacat=0&_udlo=&_udhi=&LH_BIN=1&_ftrt=901&_ftrv=1&_sabdlo=&_sabdhi=&_samilow=&_samihi=&_sadis=15&_stpos=&_sargn=-1%26saslc%3D1&_fsradio2=%26LH_LocatedIn%3D1&_salic=104&LH_SubLocation=1&_sop=12&_dmd=1&_ipg=50';
                                @endphp
                                <span hidden="true" id="url-ebay-keyword-template">{{ $urlEbayKeywordSearchtTemplate }}</span>
                                @foreach($products as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="end-item" value="{{ $item->item_id }}">
                                    </td>
                                    <td>{{ date_format($item->created_at, "Y/m/d")  }}</td>
                                    <td>
                                        @if (!empty($pathStorageFile . $item->images[0]->item_image))
                                        <img src="{{ asset($pathStorageFile . $item->images[0]->item_image)  }}" class="image-preview">
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->item_name }}
                                        <br>
                                        ItemID: {{ $item->item_id }}&nbsp;&nbsp;&nbsp;&nbsp;JAN/EAN: {{ $item->jan_upc }}
                                        <br>
                                        <form class="form-inline" role="form">
                                            <a href="{{ config('api_info.ebay_url') }}{{ str_slug($item->item_name, '-') }}/{{ $item->item_id }}" target="_blank">商品ページ </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="https://www.ebay.com/sch/i.html?_from=R40&_nkw={{ $item->jan_upc }}&_in_kw=1&_ex_kw=&_sacat=0&_udlo=&_udhi=&LH_BIN=1&_ftrt=901&_ftrv=1&_sabdlo=&_sabdhi=&_samilow=&_samihi=&_sadis=15&_stpos=&_sargn=-1%26saslc%3D1&_fsradio2=%26LH_LocatedIn%3D1&_salic=104&LH_SubLocation=1&_sop=12&_dmd=1&_ipg=50" target="_blank">ebay JAN検索</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="{{ str_replace('KEYWORD', $item->keyword, $urlEbayKeywordSearchtTemplate) }}" target="_blank" id="ebay_keyword_{{$item->id}}">ebayキーワード検索</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="form-group input-group">
                                                {!! Form::text('keyword', old('keyword', isset($item->keyword) ? $item->keyword : ''), ['class' => 'form-control keyword', 'data-id' => $item->id]) !!}
                                                {{-- <span class="input-group-addon">％</span> --}}
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->price }} @lang('view.usd')</td>
                                    <td>{{ $item->original_type ? $originType[$item->original_type] : '' }}</td>
                                    <td>{{ $item->buy_price }} @lang('view.man')</td>
                                    <td>{{ $item->ship_fee }} @lang('view.man')</td>
                                    <td>{{ $item->temp_profit }} @lang('view.man')</td>
                                    <td>{{ $item->min_price }}@lang('view.man') 〜 {{ $item->max_price }}@lang('view.man')</td>
                                    <td>{{ $monitoringType[$item->monitor_type] }}</td>
                                    <td class="width-83 ">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                            <a href="{{ route('admin.product.edit-item', ['itemId' => $item->id]) }}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                            @if ($isMonitoring)
                                            <a href="{{ route('admin.product.show-page-setting', ['itemId' => $item->id]) }}" class="btn btn-primary"><i class="fa fa-cog"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="text-center">
                            @if ($products->count())
                                {{ $products->render("pagination::bootstrap-4") }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal modal-loading"></div>
    </section>
@endsection
@section('script')
<script src="{{asset('lib/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/list-product.js') }}"></script>
<script>
    var urlDownloadCsv = "{{ route('admin.product.export-csv') }}";
    var urlUpdateItem  = "{{ route('admin.product.update') }}";
    var urlListProduct = "{{ route('admin.product.show-page-product-list') }}";
    var urlEndItem     = "{{ route('admin.product.end-item') }}";
</script>
@endsection
