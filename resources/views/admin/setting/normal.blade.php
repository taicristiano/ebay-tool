@extends('layouts.default')
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.upload_csv')])
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
                            <p>Ebayアクセストークン取得</p>
                            <button class="btn btn-primary" type="submit"> Ebayに連携する</button>
                        </div>
                        </form>
                        @else
                            <p>Ebayアクセストークン取得</p>
                            <button class="btn btn-primary" type="button"> 連携済み</button>
                        @endif
                    </div>
                    <form action="{{ route('admin.user.normal_setting_post') }}" method="POST" role="form">
                        @csrf
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::hidden('id', $setting->id) !!}
                            <label for="store_id">出店形式 <span class="text-danger">(*)</span></label>
                            {!! Form::select("store_id", $storeOption, old('store_id', isset($setting->store_id) ? $setting->store_id : ''), ['class' => 'form-control', 'id' => 'store_id']) !!}
                            {!! $errors->first('store_id') ? '<p class="text-danger">'. $errors->first('store_id') .'</p>' : ''!!}
                        </div>
                        <label for="paypal_fee_rate">割合 <span class="text-danger">(*)</span></label>
                        <div class="form-group input-group">
                            {!! Form::text('paypal_fee_rate', old('paypal_fee_rate', isset($setting->paypal_fee_rate) ? $setting->paypal_fee_rate : ''), ['class' => 'form-control']) !!}
                            <span class="input-group-addon">％</span>
                        </div>
                        {!! $errors->first('paypal_fee_rate') ? '<p class="text-danger">'. $errors->first('paypal_fee_rate') .'</p>' : ''!!}
                        <label for="ex_rate_diff">固定値 <span class="text-danger">(*)</span></label>
                        <div class="form-group input-group">
                            {!! Form::text('paypal_fixed_fee', old('paypal_fixed_fee', isset($setting->paypal_fixed_fee) ? $setting->paypal_fixed_fee : ''), ['class' => 'form-control']) !!}
                            <span class="input-group-addon">円</span>
                        </div>
                        {!! $errors->first('paypal_fixed_fee') ? '<p class="text-danger">'. $errors->first('paypal_fixed_fee') .'</p>' : ''!!}
                        <label for="ex_rate_diff">為替調整 <span class="text-danger">(*)</span></label>
                        <div class="form-group input-group">
                            {!! Form::text('ex_rate_diff', old('ex_rate_diff', isset($setting->ex_rate_diff) ? $setting->ex_rate_diff : ''), ['class' => 'form-control']) !!}
                            <span class="input-group-addon">円</span>
                        </div>
                        {!! $errors->first('ex_rate_diff') ? '<p class="text-danger">'. $errors->first('ex_rate_diff') .'</p>' : ''!!}
                        <label for="gift_discount">ギフト件割引率 <span class="text-danger">(*)</span></label>
                        <div class="form-group input-group">
                            {!! Form::text('gift_discount', old('gift_discount', isset($setting->gift_discount) ? $setting->gift_discount : ''), ['class' => 'form-control']) !!}
                            <span class="input-group-addon">%</span>
                        </div>
                        {!! $errors->first('gift_discount') ? '<p class="text-danger">'. $errors->first('gift_discount') .'</p>' : ''!!}
                        <label for="duration">販売期間 <span class="text-danger">(*)</span></label>
                        <div class="form-group input-group">
                            {!! Form::select("duration", $durationOption, old('duration', isset($setting->duration) ? $setting->duration : ''), ['class' => 'form-control', 'id' => 'duration']) !!}
                            <span class="input-group-addon">日</span>
                        </div>
                        {!! $errors->first('duration') ? '<p class="text-danger">'. $errors->first('duration') .'</p>' : ''!!}
                        <div class="form-group">
                            <label for="quantity">個数 <span class="text-danger">(*)</span></label>
                            {!! Form::text('quantity', old('quantity', isset($setting->quantity) ? $setting->quantity : ''), ['class' => 'form-control']) !!}
                            {!! $errors->first('quantity') ? '<p class="text-danger">'. $errors->first('quantity') .'</p>' : ''!!}
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="button" id="btn-get-policy" {{ $isShowButtonGetToken ? 'disabled': ''}}>ビジネスポリシー取得</button>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <div class="form-group text-center">
                            <button class="btn btn-primary" type="submit">保存</button>
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