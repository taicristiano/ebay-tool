@extends('layouts.default')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {{ __('view.' . (isset($user) ? 'update' : 'create')) . ' ' . __('view.user') }}
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
                    {{ __('view.user_management') }}
                </a>
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <form action="{{ isset($user) ? route('admin.user.update', $user->id) : route('admin.user.create') }}" class="col-xs-12 col-md-8 col-md-offset-2" method="POST" role="form">
            	@csrf
                <div class="col-xs-12 box">
                	<div class="form-group">
                        <label>
                            {{ __('view.type') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::select("type", $typeOptions, old('type', isset($user) ? $user->type : ''), ['class' => 'form-control', 'id' => 'select-type']) !!}
                        {!! $errors->first('type') ? '<p class="text-danger">'. $errors->first('type') .'</p>' : ''!!}
                    </div>
                    <div class="form-group" id="group-select-authorization" {{ old('type', isset($user) ? $user->type : '') != $typeGuestAdmin ? 'hidden' : '' }}>
                        <label>
                            {{ __('view.authorization') }}
                        </label>
                        {!! Form::select("category[]", $categoryOptions, old('category', isset($user) ? $user->authorization : []), ['multiple','class' => 'form-control', 'id' => 'select-category', 'size' => 3]) !!}
                        {!! $errors->first('category') ? '<p class="text-danger">'. $errors->first('category') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user_name') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('user_name', old('user_name', isset($user) ? $user->user_name : ''), ['class' => 'form-control']) !!}
                    	{!! $errors->first('user_name') ? '<p class="text-danger">'. $errors->first('user_name') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.password') }} {!! !isset($user) ? '<span class="text-danger">(*)</span>': '' !!}
                        </label>
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    	{!! $errors->first('password') ? '<p class="text-danger">'. $errors->first('password') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.password_confirmation') }} {!! !isset($user) ? '<span class="text-danger">(*)</span>': '' !!}
                        </label>
                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    	{!! $errors->first('password_confirmation') ? '<p class="text-danger">'. $errors->first('password_confirmation') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">
                            {{ __('view.submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @endsection
</div>
@section('script')
<script>
    var typeGuestAdmin = '{{ $typeGuestAdmin }}'
</script>
<script src="{{ asset('js/user.js') }}"></script>
@endsection