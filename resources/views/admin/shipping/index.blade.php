@extends('layouts.default')
@section('title')
{{ __('side_bar.shipping') }}
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('side_bar.shipping')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <!-- <h3 class="box-title">{{ __('view.list') }}</h3> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="form-inline" id="filter-post" role="form" method="GET">
                    <a href="{{ route('admin.shipping.create') }}" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>{{ __('view.shipping.create') }}</a>
                </form>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped table-align-center">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            @if($userLoggedIn->isSuperAdmin())
                            <th>{{ __('view.user.user_name') }}</th>
                            @endif
                            <th>{{ __('view.shipping.shipping_name') }}</th>
                            <th>{{ __('view.shipping.max_size') }}</th>
                            <th>{{ __('view.shipping.side_max_size') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!isset($shippings) || !count($shippings))
                        <tr>
                            <td colspan="5" align="center">{{ __('message.no_data') }}</td>
                        </tr>
                        @else
                        @foreach($shippings as $shipping)
                        <tr>
                            <td>{{ $shipping->id }}</td>
                            @if($userLoggedIn->isSuperAdmin())
                            <th><a href="{{ route('admin.user.update', $shipping->user_id) }}">{{ $shipping->user_name }}</a></th>
                            @endif
                            <td>{{ $shipping->shipping_name }}</td>
                            <td>{{ $shipping->max_size }}</td>
                            <td>{{ $shipping->side_max_size }}</td>
                            <td class="td-action">
                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <a href="{{ route('admin.shipping_fee.index', $shipping->id) }}" class="btn btn-success"><i class="fa fa-money"></i></a>
                                    <a href="{{ route('admin.shipping.update', $shipping->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm('{{ __('message.delete_confirm') }}') ? document.getElementById('form-{{ $shipping->id }}').submit() : false" class="btn btn-danger"><i class="fa fa-trash"></i></a></form>
                                </div>
                                <form method="POST" id="form-{{ $shipping->id }}" action="{{ route('admin.shipping.delete', $shipping->id) }}">@method('DELETE') @csrf</form>
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
