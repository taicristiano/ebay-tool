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
                    <div class="box-header with-border">@lang('view.product_list')</div>
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
                                    <th>{{ __('view.shipping_cost') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!isset($products) || !count($products))
                                <tr>
                                    <td colspan="9" align="center">{{ __('message.no_data') }}</td>
                                </tr>
                                @else
                                @foreach($products as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="end-item" value="{{ $item->item_id }}">
                                    </td>
                                    <td>{{ date_format($item->created_at, "Y/m/d")  }}</td>
                                    <td>
                                        <img src="{{ asset($pathStorageFile . $item->images[0]->item_image) }}" class="image-preview">
                                    </td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->price }} @lang('view.usd')</td>
                                    <td>{{ $originType[$item->original_type] }}</td>
                                    <td>{{ $item->buy_price }} @lang('view.man')</td>
                                    <td>{{ __('view.shipping_cost') }}</td>
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
    </section>
@endsection
@section('script')
<script src="{{asset('lib/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{ asset('js/list-product.js') }}"></script>
<script>
    var urlDownloadCsv = "{{ route('admin.product.export-csv') }}";
</script>
@endsection
