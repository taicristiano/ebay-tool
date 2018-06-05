@extends('auth.layouts.default')
@section('title', 'Ebay Tool')
@section('class-body', 'login-page')
@section('head')
<link href="{{asset('css/auth/login.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box-body">
        <h3 class="text-center">{{ __('login.title') }}</h3>
        <form action="{{ route('login') }}" method="POST" role="form">
            @csrf
            <div class="form-horizontal login-form">
                <div class="form-group">
                    <label class="col-sm-3">{{ __('login.login_id') }}</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3">{{ __('login.Password') }}</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                @if ($errors->has('email'))
                    <span class="help-block error">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                @if ($errors->login->first())
                    <span class="help-block error">
                        <strong>{{ $errors->login->first() }}</strong>
                    </span>
                @endif
            </div>
            <div class="login-footer">
                <button type="submit" class="btn btn-primary">{{ __('login.Login') }}</button>
                <span>{!! __('login.note') !!}</span>
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
@endsection