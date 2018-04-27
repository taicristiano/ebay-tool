@extends('layouts.default')
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ __('side_bar.user_manager') }}
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
                    {{ __('side_bar.user_manager') }}
                </a>
            </li>    </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ __('view.list') }}</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <form class="form-inline" id="filter-post" role="form" method="GET">
                        {!! Form::select("type", $typeOptions, old('type'), ['class' => 'form-control', 'id' => 'select-type']) !!}
                        {!! Form::text('user_name', old('user_name'), ['class' => 'form-control', 'placeholder' => __('view.placeholder_user_name'), 'id' => 'user-name']) !!}
                        <button class="btn btn-primary"><i class="fa fa-search"></i> {{ __('view.filt') }}</button>
                        <a href="{{ route('admin.user.create') }}" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>{{ __('view.create') }}</a>
                    </form>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <form class="form-inline pull-right" id="export-csv" role="form" method="GET" action="{{ route('admin.user.export-csv') }}">
                        <input type="hidden" name="type_csv" id="type_csv">
                        <input type="hidden" name="type_user" id="type_user">
                        <input type="hidden" name="user_name" id="user_name">
                        <button class="btn btn-primary" id="btn-csv-full"><i class="fa fa-download"></i> {{ __('view.csv_full') }}</button>
                        <button class="btn btn-primary" id="btn-csv-simple"><i class="fa fa-download"></i> {{ __('view.csv') }}</button>
                    </form>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped table-align-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('view.user_name') }}</th>
                            <th>{{ __('view.type') }}</th>
                            <th>{{ __('view.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!isset($users) || !count($users))
                        <tr>
                            <td colspan="5" align="center">{{ __('message.no_data') }}</td>
                        </tr>
                        @else
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->user_name }}</td>
                            <td>{{ $user->renderTypeAsString() }}</td>
                            <td class="td-action">
                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <a href="{{ route('admin.user.update', $user->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm('{{ __('message.delete_confirm') }}') ? document.getElementById('form-{{ $user->id }}').submit() : false" class="btn btn-danger"><i class="fa fa-trash"></i></a></form>
                                </div>
                                <form method="POST" id="form-{{ $user->id }}" action="{{ route('admin.user.delete', $user->id) }}">@method('DELETE') @csrf</form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                {!! isset($users) ? $users->links() : '' !!}
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section>
    <!-- /.content -->
</div>
@endsection

@section('script')
<script>
    var urlDownloadCsv = "{{ route('admin.user.export-csv') }}";
</script>
<script src="{{ asset('js/export-csv.js') }}"></script>
@endsection
