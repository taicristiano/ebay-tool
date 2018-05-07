@extends('layouts.default')
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('side_bar.normal_setting')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="box box-success">
                    <div class="box-header with-border">
                        @if($isShowButtonGetToken)
                        <form action="{{ route('admin.user.api-get-session-id') }}" method="POST" role="form">
                            @csrf
                            <div class="form-group">
                                <p>@lang('view.get_ebay_access_token')</p>
                                <button class="btn btn-primary" type="submit">@lang('view.link_to_ebay')</button>
                            </div>
                        </form>
                        @else
                        <p>@lang('view.get_ebay_access_token')</p>
                        <button class="btn btn-primary" type="button">@lang('view.collaborated')</button>
                        @endif
                    </div>
                    <form action="{{ route('admin.user.normal_setting_post') }}" method="POST" role="form">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                {!! Form::hidden('id', $setting->id) !!}
                                <label for="store_id">@lang('view.store_id') <span class="text-danger">(*)</span></label>
                                {!! Form::select("store_id", $storeOption, old('store_id', isset($setting->store_id) ? $setting->store_id : ''), ['class' => 'form-control', 'id' => 'store_id']) !!}
                                {!! $errors->first('store_id') ? '
                                <p class="text-danger">'. $errors->first('store_id') .'</p>
                                ' : ''!!}
                            </div>
                            <label for="paypal_fee_rate">@lang('view.paypal_fee_rate') <span class="text-danger">(*)</span></label>
                            <div class="form-group input-group">
                                {!! Form::text('paypal_fee_rate', old('paypal_fee_rate', isset($setting->paypal_fee_rate) ? $setting->paypal_fee_rate : ''), ['class' => 'form-control']) !!}
                                <span class="input-group-addon">％</span>
                            </div>
                            {!! $errors->first('paypal_fee_rate') ? '
                            <p class="text-danger">'. $errors->first('paypal_fee_rate') .'</p>
                            ' : ''!!}
                            <label for="ex_rate_diff">@lang('view.paypal_fixed_fee') <span class="text-danger">(*)</span></label>
                            <div class="form-group input-group">
                                {!! Form::text('paypal_fixed_fee', old('paypal_fixed_fee', isset($setting->paypal_fixed_fee) ? $setting->paypal_fixed_fee : ''), ['class' => 'form-control']) !!}
                                <span class="input-group-addon">@lang('view.yen')</span>
                            </div>
                            {!! $errors->first('paypal_fixed_fee') ? '
                            <p class="text-danger">'. $errors->first('paypal_fixed_fee') .'</p>
                            ' : ''!!}
                            <label for="ex_rate_diff">@lang('view.ex_rate_diff') <span class="text-danger">(*)</span></label>
                            <div class="form-group input-group">
                                {!! Form::text('ex_rate_diff', old('ex_rate_diff', isset($setting->ex_rate_diff) ? $setting->ex_rate_diff : ''), ['class' => 'form-control']) !!}
                                <span class="input-group-addon">@lang('view.yen')</span>
                            </div>
                            {!! $errors->first('ex_rate_diff') ? '
                            <p class="text-danger">'. $errors->first('ex_rate_diff') .'</p>
                            ' : ''!!}
                            <label for="gift_discount">@lang('view.gift_discount') <span class="text-danger">(*)</span></label>
                            <div class="form-group input-group">
                                {!! Form::text('gift_discount', old('gift_discount', isset($setting->gift_discount) ? $setting->gift_discount : ''), ['class' => 'form-control']) !!}
                                <span class="input-group-addon">%</span>
                            </div>
                            {!! $errors->first('gift_discount') ? '
                            <p class="text-danger">'. $errors->first('gift_discount') .'</p>
                            ' : ''!!}
                            <label for="duration">@lang('view.duration') <span class="text-danger">(*)</span></label>
                            <div class="form-group input-group">
                                {!! Form::select("duration", $durationOption, old('duration', isset($setting->duration) ? $setting->duration : ''), ['class' => 'form-control', 'id' => 'duration']) !!}
                                <span class="input-group-addon">@lang('view.day')</span>
                            </div>
                            {!! $errors->first('duration') ? '
                            <p class="text-danger">'. $errors->first('duration') .'</p>
                            ' : ''!!}
                            <div class="form-group">
                                <label for="quantity">@lang('view.quantity') <span class="text-danger">(*)</span></label>
                                {!! Form::text('quantity', old('quantity', isset($setting->quantity) ? $setting->quantity : ''), ['class' => 'form-control']) !!}
                                {!! $errors->first('quantity') ? '
                                <p class="text-danger">'. $errors->first('quantity') .'</p>
                                ' : ''!!}
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="btn-get-policy" {{ $isShowButtonGetToken ? 'disabled': ''}}>@lang('view.acquire_business_policy')</button>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group text-center col-sm-4 col-sm-offset-4">
                                <button class="btn btn-block btn-primary btn-lg" type="submit">@lang('view.save')</button>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('admin.user.api-get-policy') }}" method="POST" role="form" id="form-get-policy">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endsection
</div>
@section('script')
<script src="{{ asset('js/normal-setting.js') }}"></script>
@endsection