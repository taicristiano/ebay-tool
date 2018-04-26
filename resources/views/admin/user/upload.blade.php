@extends('layouts.default')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            {{ __('side_bar.create_user') }}
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
                    {{ __('side_bar.create_user') }}
                </a>
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            
        </div>
    </section>
    @endsection
</div>
@section('script')
<script src="{{ asset('js/user.js') }}"></script>
@endsection