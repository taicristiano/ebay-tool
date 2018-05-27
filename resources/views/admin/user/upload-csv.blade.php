@extends('layouts.default')
@section('title')
{{ __('view.create_many') }}
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.create_many')])
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="box box-success">
                    <div class="box-header text-center">
                      <h2 class="box-title">{{ __('view.create_many') }}</h2>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form class="form-inline" enctype="multipart/form-data" role="form" method="POST" action="{{ route('admin.user.upload-csv') }}">
                            @csrf
                            <p class="col-xs-12 col-md-8 col-md-offset-1">&#60;&#60;{{ __('view.csv_create_many') }}&#62;&#62;</p>
                            <div class="input-group image-preview col-xs-12 col-md-8 col-md-offset-1" data-original-title="" title="">
                                <input type="text" class="form-control" id="file_name_csv" disabled="disabled">
                                <span class="input-group-btn">
                                    <div class="btn btn-default custom-btn-choose-file">
                                        <span class="fa fa-file image-preview-input-title"></span>
                                        <input type="file" name="file_csv" id="file_csv">
                                    </div>
                                </span>
                            </div>
                            <button class="btn btn-primary" type="submit"><i class="fa fa-upload"></i> {{ __('view.create_many') }}</button>
                            <div class="col-xs-12 col-md-8 col-md-offset-1 error">{{ $errors->first('file_csv') }}</div>
                        <!-- /.box -->
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
</div>
@endsection
@section('script')
    <script src="{{ asset('js/upload-csv.js') }}"></script>
@endsection