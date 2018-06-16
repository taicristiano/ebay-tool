@php
    $urlCurrent = url()->current();
    $urlUserIndex = route('admin.user.index');
    $urlUserCreateMany = route('admin.user.show-page-upload-csv');
    $urlUserCreate = route('admin.user.create');
    $isActiveMenuUser = false;
    if (in_array($urlCurrent, [$urlUserIndex, $urlUserCreateMany, $urlUserCreate])) {
        $isActiveMenuUser = true;
    }

    $urlSettingNormal = route('admin.user.normal_setting');
    $isActiveMenuSetting = false;
    if (in_array($urlCurrent, [$urlSettingNormal])) {
        $isActiveMenuSetting = true;
    }
@endphp
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">{{ __('side_bar.main') }}</li>
            <li @if($urlCurrent == url('')) class="active" @endif>
                <a href="{{url('')}}">
                <i class="fa fa-dashboard active"></i> <span>{{ __('side_bar.top') }}</span>
                </span>
                </a>
            </li>
            @can('user_manager')
            <li class="treeview @if($isActiveMenuUser)active menu-open @endif" id="admin.users">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>{{ __('side_bar.user_manager') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="padding-left: 20px">
                    <li @if($urlCurrent == $urlUserIndex) class="active" @endif><a href="{{ $urlUserIndex }}"><i class="fa fa-list-ul"></i>{{ __('side_bar.list_user') }}</a></li>
                    <li @if($urlCurrent == $urlUserCreateMany) class="active" @endif><a href="{{ $urlUserCreateMany }}"><i class="fa fa-plus-square"></i>{{ __('side_bar.create_many_user') }}</a></li>
                </ul>
            </li>
            @endcan
            @can('post_product')
            <li id="admin.product.show-page-post-product">
                <a href="{{ route('admin.product.show-page-post-product') }}">
                    <i class="fa fa-upload"></i>{{ __('side_bar.post_product') }}
                </a>
            </li>
            @endcan
            @can('product_manager')
            <li id="admin.product.show-page-product-list">
                <a href="{{ route('admin.product.show-page-product-list')}}">
                    <i class="fa fa-truck"></i>
                    <span>{{ __('side_bar.product_manager') }}</span>
                </a>
            </li>
            @endcan
            @can('order')
            <li class="treeview" id="admin.order">
                <a href="#">
                    <i class="fa fa-cart-plus"></i>
                    <span>{{ __('side_bar.order_manager') }}</span>
                </a>
            </li>
            @endcan
            @can('revenue')
            <li class="treeview" id="admin.revenue">
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span>{{ __('side_bar.revenue_manager') }}</span>
                </a>
            </li>
            @endcan
            @can('setting')
            <li class="treeview @if($isActiveMenuSetting)active menu-open @endif" id="admin.setting|admin.shipping|admin.template">
                <a href="#">
                    <i class="fa fa-gears"></i>
                    <span>{{ __('side_bar.setting') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="padding-left: 20px">
                    <li><a href="{{ route('admin.shipping.index') }}"><i class="fa fa-cog"></i>{{ __('side_bar.shipping') }}</a></li>
                    <li @if($urlCurrent == $urlSettingNormal) class="active" @endif><a href="{{ $urlSettingNormal }}"><i class="fa fa-list-ul"></i>{{ __('side_bar.normal_setting') }}</a></li>
                    <li><a href="{{ route('admin.template.index') }}"><i class="fa fa-file-text-o"></i>{{ __('side_bar.template') }}</a></li>
                </ul>
            </li>
            @endcan
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
