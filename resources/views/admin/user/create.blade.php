@extends('layouts.default')
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.user.' . (isset($user) ? 'create_title' : 'create_title'))])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <form action="{{ isset($user) ? route('admin.user.update', $user->id) : route('admin.user.create') }}" class="col-xs-12 col-md-8 col-md-offset-2" method="POST" role="form">
            	@csrf
                <div class="col-xs-12 box">
                	<div class="form-group">
                        <label>
                            {{ __('view.user.type') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::select("type", $typeOptions, old('type', isset($user) ? $user->type : ''), ['class' => 'form-control', 'id' => 'select-type']) !!}
                        {!! $errors->first('type') ? '<p class="text-danger">'. $errors->first('type') .'</p>' : ''!!}
                    </div>
                    <div class="form-group" id="group-select-authorization" {{ old('type', isset($user) ? $user->type : '') != $typeGuestAdmin ? 'hidden' : '' }}>
                        <label>
                            {{ __('view.user.authorization') }}
                        </label>
                        {!! Form::select("category[]", $categoryOptions, old('category', isset($user) ? $user->authorization : []), ['multiple','class' => 'form-control', 'id' => 'select-category', 'size' => 3]) !!}
                        {!! $errors->first('category') ? '<p class="text-danger">'. $errors->first('category') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.user_name') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('user_name', old('user_name', isset($user) ? $user->user_name : ''), ['class' => 'form-control']) !!}
                    	{!! $errors->first('user_name') ? '<p class="text-danger">'. $errors->first('user_name') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.name_kana') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('name_kana', old('name_kana', isset($user) ? $user->name_kana : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('name_kana') ? '<p class="text-danger">'. $errors->first('name_kana') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.introducer_id') }}
                        </label>
                        {!! Form::select("introducer_id", session('introducer') ? session('introducer') : [], old('introducer_id', isset($user) ? $user->introducer_id : ''), ['class' => 'form-control', 'id' => 'select-introducer']) !!}
                        {!! $errors->first('introducer_id') ? '<p class="text-danger">'. $errors->first('introducer_id') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.ebay_account') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('ebay_account', old('ebay_account', isset($user) ? $user->ebay_account : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('ebay_account') ? '<p class="text-danger">'. $errors->first('ebay_account') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.start_date') }}
                        </label>
                        @php
                        $startDate = old('start_date', isset($user) ? $user->start_date : '');
                        @endphp
                        {!! Form::text('start_date', $startDate ? date('Y/m/d', strtotime($startDate)) : '', ['class' => 'form-control', 'id' => 'input-start-date']) !!}
                        {!! $errors->first('start_date') ? '<p class="text-danger">'. $errors->first('start_date') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.tel') }}
                        </label>
                        {!! Form::text('tel', old('tel', isset($user) ? $user->tel : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('tel') ? '<p class="text-danger">'. $errors->first('tel') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.email') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::email('email', old('email', isset($user) ? $user->email : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('email') ? '<p class="text-danger">'. $errors->first('email') .'</p>' : ''!!}
                    </div>
                    <div class="form-group" id="group-regist-limit" {{ old('type', isset($user) ? $user->type : '') != $typeGuestAdmin ? 'hidden' : '' }}>
                        <label>
                            {{ __('view.user.regist_limit') }}
                        </label>
                        {!! Form::number('regist_limit', old('regist_limit', isset($user) ? $user->regist_limit : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('regist_limit') ? '<p class="text-danger">'. $errors->first('regist_limit') .'</p>' : ''!!}
                    </div>
                    <div class="form-group" id="group-post-limit" {{ old('type', isset($user) ? $user->type : '') != $typeGuestAdmin ? 'hidden' : '' }}>
                        <label>
                            {{ __('view.user.post_limit') }}
                        </label>
                        {!! Form::number('post_limit', old('post_limit', isset($user) ? $user->post_limit : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('post_limit') ? '<p class="text-danger">'. $errors->first('post_limit') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.password') }} {!! !isset($user) ? '<span class="text-danger">(*)</span>': '' !!}
                        </label>
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    	{!! $errors->first('password') ? '<p class="text-danger">'. $errors->first('password') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.password_confirmation') }} {!! !isset($user) ? '<span class="text-danger">(*)</span>': '' !!}
                        </label>
                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    	{!! $errors->first('password_confirmation') ? '<p class="text-danger">'. $errors->first('password_confirmation') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.user.memo') }}
                        </label>
                        {!! Form::textarea('memo', old('memo', isset($user) ? $user->memo : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('memo') ? '<p class="text-danger">'. $errors->first('memo') .'</p>' : ''!!}
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
@section('head')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datepicker/datepicker3.css') }}">
@endsection
@section('script')
<script>
    var typeGuestAdmin = '{{ $typeGuestAdmin }}'
    var fetchUserUrl = '{{ route("admin.user.fetch") }}'
</script>
<script src="{{ asset('adminlte/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/user.js') }}"></script>
@endsection