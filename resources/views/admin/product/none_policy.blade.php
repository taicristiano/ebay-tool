@extends('layouts.default')
@section('title')
@lang('view.none_policy')
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.none_policy')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="box box-success">
                    <div class="box-body">
                        <p>You do not get policy. Please go to <a href="{{ route('admin.user.normal_setting') }}">setting</a> get policy</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
</div>
