@extends('layouts.default')
@section('head')
<link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/all.css')}}">
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => 'Post product'])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">Post product</div>
                    <div class="box-body">
                        <form class="form-inline" id="filter-post" role="form" method="GET">
                            {!! Form::text('item_id', old('item_id'), ['class' => 'form-control', 'placeholder' => __('view.itemID')]) !!}
                            <button class="btn btn-primary"><i class="fa fa-info-circle"></i> {{ __('view.filter') }}</button>
                            &emsp;&emsp;&emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" checked value="1">
                                @lang('view.yahoo_auction')
                            </label>
                            &emsp;&emsp;<label>
                                <input type="radio" name="type" class="minimal type" value="2">
                                @lang('view.amazon')
                            </label>
                            &emsp;&emsp;&emsp;&emsp;{!! Form::text('id_ebay_or_amazon', old('id_ebay_or_amazon'), ['class' => 'form-control', 'placeholder' => __('view.itemID')]) !!}
                            <button class="btn btn-primary"><i class="fa fa-info-circle"></i> {{ __('view.image_acquisition') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
</div>
@section('script')
<script src="{{asset('adminlte/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('js/normal-setting.js') }}"></script>
<script>
    $('input[type="radio"].minimal').iCheck({
        radioClass: 'iradio_minimal-blue'
    });
</script>
@endsection