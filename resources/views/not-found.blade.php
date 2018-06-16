@extends('layouts.default')
@section('title')
@lang('view.page_not_found')
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.page_not_found')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-success">
                    <div class="box-body">
                        @lang('view.page_not_found')
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
</div>
