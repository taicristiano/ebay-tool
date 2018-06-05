@extends('layouts.default')
@section('title')
{{ __('view.shipping.list_fee') }}
@endsection
@section('content')
<div class="content-wrapper">
    @include('layouts.component.header-content', ['text' => __('view.shipping.list_fee')])
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <!-- <h3 class="box-title">{{ __('view.list') }}</h3> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="form-inline" id="filter-post" role="form" method="GET">
                    <a href="{{ route('admin.shipping_fee.create', $shipping->id) }}" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>{{ __('view.shipping.create_fee') }}</a>
                </form>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped table-align-center">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('view.shipping.weight') }}</th>
                            <th>{{ __('view.shipping.ship_fee') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!isset($shippingFees) || !count($shippingFees))
                        <tr>
                            <td colspan="4" align="center">{{ __('message.no_data') }}</td>
                        </tr>
                        @else
                        @foreach($shippingFees as $shippingFee)
                        <tr>
                            <td>{{ $shippingFee->id }}</td>
                            <td>{{ $shippingFee->weight }}</td>
                            <td>{{ $shippingFee->ship_fee }}</td>
                            <td class="td-action">
                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <a href="{{ route('admin.shipping_fee.update', [$shipping->id, $shippingFee->id]) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm('{{ __('message.delete_confirm') }}') ? document.getElementById('form-{{ $shippingFee->id }}').submit() : false" class="btn btn-danger"><i class="fa fa-trash"></i></a></form>
                                </div>
                                <form method="POST" id="form-{{ $shippingFee->id }}" action="{{ route('admin.shipping_fee.delete', [$shipping->id, $shippingFee->id]) }}">@method('DELETE') @csrf</form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                {!! isset($shippingFees) ? $shippingFees->links() : '' !!}
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section>
    <!-- /.content -->
</div>
@endsection
