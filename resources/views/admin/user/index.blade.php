@extends('layouts.default')
@section('title')
{{ __('side_bar.user_manager') }}
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('side_bar.user_manager')])
    <!-- Main content -->
    <section class="content">
        @include('layouts.component.alert')
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <!-- <h3 class="box-title">{{ __('view.list') }}</h3> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <form class="form-inline" id="filter-post" role="form" method="GET">
                        {!! Form::select("type", $typeOptions, old('type'), ['class' => 'form-control', 'id' => 'select-type']) !!}
                        {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => __('view.user.placeholder_search')]) !!}
                        <button class="btn btn-primary"><i class="fa fa-search"></i> {{ __('view.filter') }}</button>
                        <a href="{{ route('admin.user.create') }}" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>{{ __('view.create') }}</a>
                    </form>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                            <th>{{ __('view.user.user_code') }}</th>
                            <th>{{ __('view.user.customer_name') }}</th>
                            <th>{{ __('view.user.name_kana') }}</th>
                            <th>{{ __('view.user.email') }}</th>
                            <th>{{ __('view.user.tel') }}</th>
                            <th>{{ __('view.user.type') }}</th>
                            <th>{{ __('view.user.memo') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!isset($users) || !count($users))
                        <tr>
                            <td colspan="8" align="center">{{ __('message.no_data') }}</td>
                        </tr>
                        @else
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->user_code }}</td>
                            <td>{{ $user->user_name }}</td>
                            <td>{{ $user->name_kana }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->tel }}</td>
                            <td>{{ $user->renderTypeAsString() }}</td>
                            <td>{{ $user->memo }}</td>
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
