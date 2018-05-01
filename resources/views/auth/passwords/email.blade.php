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
        <form action="{{ route('password.email') }}" method="POST" role="form">
            @csrf
            <div class="form-horizontal login-form">
                <div class="form-group">
                    <label class="col-sm-4">{{ __('view.user.email') }}</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="email">
                        @if ($errors->has('email'))
                            <span class="help-block error">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                @if (session('status'))
                    <span class="help-block success">
                        {{ session('status') }}
                    </span>
                @endif
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-4">
                        <button type="submit" class="btn btn-primary">{{ __('view.send_password_reset_link') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
@endsection