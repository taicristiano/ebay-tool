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
        <form action="{{ route('password.request') }}" method="POST" role="form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-horizontal login-form">
                <div class="form-group">
                    <label class="col-sm-4">{{ __('view.user.email') }}</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="help-block error">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">{{ __('login.Password') }}</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password">
                        @if ($errors->has('password'))
                            <span class="help-block error">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">{{ __('view.user.password_confirmation') }}</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password_confirmation">
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block error">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-primary" style="width: 100%">{{ __('view.reset_password') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
@endsection