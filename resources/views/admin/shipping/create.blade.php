@extends('layouts.default')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {{ __('view.shipping.' . (isset($shipping) ? 'update' : 'create')) }}
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
            <form action="{{ isset($shipping) ? route('admin.shipping.update', $shipping->id) : route('admin.shipping.create') }}" class="col-xs-12 col-md-8 col-md-offset-2" method="POST" role="form">
            	@csrf
                <div class="col-xs-12 box">
                	<div class="form-group">
                        <label>
                            {{ __('view.shipping.shipping_name') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('shipping_name', old('shipping_name', isset($shipping) ? $shipping->shipping_name : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('shipping_name') ? '<p class="text-danger">'. $errors->first('shipping_name') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.shipping.max_size') }}
                        </label>
                        {!! Form::text('max_size', old('max_size', isset($shipping) ? $shipping->max_size : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('max_size') ? '<p class="text-danger">'. $errors->first('max_size') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.shipping.side_max_size') }}
                        </label>
                        {!! Form::text('side_max_size', old('side_max_size', isset($shipping) ? $shipping->side_max_size : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('side_max_size') ? '<p class="text-danger">'. $errors->first('side_max_size') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">
                            {{ __('view.shipping.submit') }}
                        </button>
                        <button class="btn btn-primary" type="submit" name="fee" value="1">
                            {{ __('view.shipping.submit_with_fee') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @endsection
</div>