<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">{{ __('side_bar.main') }}</li>
            <li>
                <a href="">
                <i class="fa fa-dashboard active"></i> <span>{{ __('side_bar.top') }}</span>
                </span>
                </a>
            </li>
            @can('user_manager')
            <li class="treeview" id="admin.user">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>{{ __('side_bar.user_manager') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="padding-left: 20px">
                    <li><a href="{{ route('admin.user.index') }}"><i class="fa fa-list-ul"></i>{{ __('view.list') }}</a></li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-plus-square"></i> {{ __('view.new_user') }}
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li><a href="{{ route('admin.user.create') }}"><i class="fa fa-plus-square"></i>{{ __('view.create') }}</a></li>
                            <li><a href="{{ route('admin.user.show-page-upload-csv') }}"><i class="fa fa-plus-square"></i>{{ __('view.upload_csv') }}</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.user.index') }}"><i class="fa fa-list-ul"></i>{{ __('side_bar.list_user') }}</a></li>
                    <li><a href="{{ route('admin.user.upload') }}"><i class="fa fa-plus-square"></i>{{ __('side_bar.create_user') }}</a></li>
                </ul>
            </li>
            @endcan
            <li class="treeview" id="admin.product.post">
                <a href="#">
                    <i class="fa fa-upload"></i>
                    <span>{{ __('side_bar.post_product') }}</span>
                </a>
            </li>
            @can('product_manager')
            <li class="treeview" id="admin.product">
                <a href="#">
                    <i class="fa fa-truck"></i>
                    <span>{{ __('side_bar.product_manager') }}</span>
                </a>
            </li>
            @endcan
            <li class="treeview" id="admin.order">
                <a href="#">
                    <i class="fa fa-cart-plus"></i>
                    <span>{{ __('side_bar.order_manager') }}</span>
                </a>
            </li>
            <li class="treeview" id="admin.revenue">
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span>{{ __('side_bar.revenue_manager') }}</span>
                </a>
            </li>
            @can('setting')
            <li class="treeview" id="admin.setting">
                <a href="#">
                    <i class="fa fa-gears"></i>
                    <span>{{ __('side_bar.setting') }}</span>
                </a>
            </li>
            @endcan
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>