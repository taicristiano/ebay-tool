@extends('auth.layouts.default')
@section('title')
Ebay Tool
@endsection
@section('class-body')
login-page
@endsection
@section('head')
<link rel="stylesheet" href="{{asset('css/auth/login.css')}}">
@endsection
@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>Ebay</b>Tool</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to manager</p>

    <form role="login" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible alert-margin">
            {{ session()->get('error') }}
        </div>
        @endif
        @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible alert-margin">
            {{ session()->get('message') }}
        </div>
        @endif
      <div class="form-group has-feedback">
        <input type="text" name="user_name" class="form-control{{ $errors->has('user_name') ? ' has-error' : '' }}" placeholder="{{ __('login.Email') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ($errors->has('user_name'))
            <span class="help-block error">
                <strong>{{ $errors->first('user_name') }}</strong>
            </span>
        @endif
        @if(session()->has('message'))
            <span class="help-block error">
                <strong>{{ session()->get('message') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" placeholder="{{ __('login.Password') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ($errors->has('password'))
            <span class="help-block error">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="text-center">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('login.Login') }}</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
@endsection