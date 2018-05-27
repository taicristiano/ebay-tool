@extends('layouts.default')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {{ __('view.shipping.' . (isset($shippingFee) ? 'update_fee' : 'create_fee')) }}
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">
                    <i class="fa fa-dashboard">
                    </i>
                    Home
                </a>
            </li>
            <li>
                <a href="#">
                    {{ __('side_bar.shipping') }}
                </a>
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <form action="{{ isset($shippingFee) ? route('admin.shipping_fee.update', [$shipping->id, $shippingFee->id]) : route('admin.shipping_fee.create', $shipping->id) }}" class="col-xs-12 col-md-8 col-md-offset-2" method="POST" role="form">
            	@csrf
                <div class="col-xs-12 box">
                	<div class="form-group">
                        <label>
                            {{ __('view.shipping.weight') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('weight', old('weight', isset($shippingFee) ? $shippingFee->weight : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('weight') ? '<p class="text-danger">'. $errors->first('weight') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.shipping.ship_fee') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('ship_fee', old('ship_fee', isset($shippingFee) ? $shippingFee->ship_fee : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('ship_fee') ? '<p class="text-danger">'. $errors->first('ship_fee') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">
                            {{ __('view.shipping.submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @endsection
</div>
@section('head')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datepicker/datepicker3.css') }}">
@endsection
@section('script')
<script src="{{ asset('adminlte/plugins/select2/select2.min.js') }}"></script>
@endsection