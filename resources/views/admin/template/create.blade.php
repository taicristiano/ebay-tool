@extends('layouts.default')
@section('title')
{{ __('view.template.' . (isset($template) ? 'update' : 'create')) }}
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.template.' . (isset($template) ? 'update' : 'create'))])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="row">
            <form action="{{ isset($template) ? route('admin.template.update', $template->id) : route('admin.template.create') }}" class="col-xs-12 col-md-8 col-md-offset-2" method="POST" role="form">
            	@csrf
                <div class="col-xs-12 box">
                	<div class="form-group">
                        <label>
                            {{ __('view.template.title') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::text('title', old('title', isset($template) ? $template->title : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('title') ? '<p class="text-danger">'. $errors->first('title') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <label>
                            {{ __('view.template.content') }} <span class="text-danger">(*)</span>
                        </label>
                        {!! Form::textarea('content', old('content', isset($template) ? $template->content : ''), ['class' => 'form-control']) !!}
                        {!! $errors->first('content') ? '<p class="text-danger">'. $errors->first('content') .'</p>' : ''!!}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">
                            {{ __('view.template.submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    @endsection
</div>
@section('head')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
@endsection
@section('script')
<script src="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script>
    $('[name=content]').wysihtml5({locale: "ja-JP"});
</script>
@endsection