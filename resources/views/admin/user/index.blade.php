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
                <!-- <h3 class="box-title">{{ __('view.list') }}</h3> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="form-inline" id="filter-post" role="form" method="GET">
                    {!! Form::select("type", $typeOptions, old('type'), ['class' => 'form-control', 'id' => 'select-type']) !!}
                    {!! Form::text('user_name', old('user_name'), ['class' => 'form-control', 'placeholder' => __('view.user.placeholder_user_name')]) !!}
                    <button class="btn btn-primary"><i class="fa fa-search"></i> {{ __('view.filter') }}</button>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>{{ __('view.create') }}</a>
                </form>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped table-align-center">
                    <thead>
                        <tr>
                            <th>{{ __('view.user.user_code') }}</th>
                            <th>{{ __('view.user.user_name') }}</th>
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
