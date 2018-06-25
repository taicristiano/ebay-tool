@extends('layouts.default')
@section('title')
@lang('view.product_setting')
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.product_setting')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <p>@lang('view.product_setting')</p>
                    </div>
                    <form action="{{ route('admin.product.update-setting', ['itemId' => $item['id']]) }}" method="POST" role="form">
                        @csrf
                        <div class="box-body">
                            <label>@lang('view.purchase_allowance_price')</span></label>
                            <div class="row">
                                <div class="col-md-5 col-sm-5 col-xs-12 {{ $errors->first('min_price') ? 'has-error' : '' }}">
                                    <div class="form-group">
                                        {!! Form::text('min_price', old('min_price', isset($item['min_price']) ? $item['min_price'] : ''), ['class' => 'form-control']) !!}
                                        {!! $errors->first('min_price') ? '
                                        <p class="text-danger">'. $errors->first('min_price') .'</p>
                                        ' : ''!!}
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 text-center">〜</div>
                                <div class="col-md-5 col-sm-5 col-xs-12 {{ $errors->first('max_price') ? 'has-error' : '' }}">
                                    <div class="form-group">
                                        {!! Form::text('max_price', old('max_price', isset($item['max_price']) ? $item['max_price'] : ''), ['class' => 'form-control']) !!}
                                        {!! $errors->first('max_price') ? '
                                        <p class="text-danger">'. $errors->first('max_price') .'</p>
                                        ' : ''!!}
                                    </div>
                                </div>
                            </div>
                            <label for="monitor_type">@lang('view.price_monitoring_setting') <span class="text-danger">(*)</span></label>
                            <div class="form-group {{ $errors->first('monitor_type') ? 'has-error' : '' }}">
                                {!! Form::select("monitor_type", $priceMonitoringSetting, old('monitor_type', isset($item['monitor_type']) ? $item['monitor_type'] : ''), ['class' => 'form-control', 'id' => 'monitor_type']) !!}
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group text-center col-sm-4 col-sm-offset-4">
                                <button class="btn btn-block btn-primary btn-lg" type="submit">@lang('view.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endsection
</div>
