@extends('layouts.default')
@section('title')
{{ __('side_bar.template') }}
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('side_bar.template')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <!-- <h3 class="box-title">{{ __('view.list') }}</h3> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="form-inline" id="filter-post" role="form" method="GET">
                    <a href="{{ route('admin.template.create') }}" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>{{ __('view.template.create') }}</a>
                </form>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped table-align-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('view.template.title') }}</th>
                            <th>{{ __('view.template.created_at') }}</th>
                            <th>{{ __('view.template.updated_at') }}</th>
                            <th class="td-action"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!isset($templates) || !count($templates))
                        <tr>
                            <td colspan="5" align="center">{{ __('message.no_data') }}</td>
                        </tr>
                        @else
                        @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->id }}</td>
                            <td>{{ $template->title }}</td>
                            <td>{{ $template->created_at }}</td>
                            <td>{{ $template->updated_at }}</td>
                            <td class="td-action">
                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <a href="{{ route('admin.template.update', $template->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm('{{ __('message.delete_confirm') }}') ? document.getElementById('form-{{ $template->id }}').submit() : false" class="btn btn-danger"><i class="fa fa-trash"></i></a></form>
                                </div>
                                <form method="POST" id="form-{{ $template->id }}" action="{{ route('admin.template.delete', $template->id) }}">@method('DELETE') @csrf</form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                {!! isset($shippings) ? $shippings->links() : '' !!}
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section>
    <!-- /.content -->
</div>
@endsection
