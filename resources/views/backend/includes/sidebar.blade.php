<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                {{--                <img src="{!! access()->user()->picture !!}" class="img-circle" alt="User Image"/>--}}
            </div>
            <div class="pull-left info">
                <p>{{ access()->user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control"
                       placeholder="{{ trans('strings.search_placeholder') }}"/>
                  <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i
                            class="fa fa-search"></i></button>
                  </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('menus.general') }}</li>

            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Active::pattern('admin/dashboard') }}"><a
                    href="{!!route('backend.dashboard')!!}"><span>{{ trans('menus.dashboard') }}</span></a></li>

            <li class="{{ Active::pattern('admin/dashboard') }}">
                <a href=""><span>{{ trans('menus.product.management') }}</span><i
                        class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{!!url('admin/products')!!}"><i
                                class="fa fa-circle-o"></i>{{ trans('menus.product.all') }}</a></li>
                    <li><a href="{!!url('admin/groups')!!}"><i
                                class="fa fa-circle-o"></i>{{ trans('menus.product.groups') }}</a></li>
                    <li><a href="{!!url('admin/brands')!!}"><i
                                class="fa fa-circle-o"></i>{{ trans('menus.product.brands') }}</a></li>
                    <li><a href="{!!url('admin/categories')!!}"><i
                                class="fa fa-circle-o"></i>{{ trans('menus.product.categories') }}</a></li>
                    <li><a href="{!!url('admin/attributes')!!}"><i
                                class="fa fa-circle-o"></i>{{ trans('menus.product.attributes') }}</a></li>
                </ul>
            </li>

            <li class="{{ Active::pattern('admin/merchants') }}"><a
                    href="{!! url('admin/merchants') !!}"><span>{{ trans('menus.merchant') }}</span></a></li>

            {{--<li class="{{ Active::pattern('admin/marketing') }}"><a--}}
            {{--href="{!! url('admin/marketing') !!}"><span>{{ trans('menus.marketing') }}</span></a></li>--}}

            <li class="{{ Active::pattern('admin/clients') }}"><a
                    href="{!! url('admin/clients') !!}"><span>{{ trans('menus.client') }}</span></a></li>

            <li class="{{ Active::pattern('admin/orders') }}"><a
                    href="{!! url('admin/orders')  !!}"><span>{{ trans('menus.order') }}</span></a></li>

            {{--<li class="{{ Active::pattern('admin/dashboard') }}"><a--}}
            {{--href="url('admin/attributes')"><span>{{ trans('menus.express') }}</span></a></li>--}}

            <li class="{{ Active::pattern('admin/accounts') }}"><a
                    href="{!! url('admin/accounts') !!}"><span>{{ trans('menus.account') }}</span></a></li>

            <li class="{{ Active::pattern('admin/media') }}">
                <a href=""><span>{{ trans('menus.media') }}</span><i
                        class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{!! url('admin/images') !!}"><i class="fa fa-circle-o"></i>{{ trans('menus.image') }}
                        </a></li>
                    <li><a href="{!! url('admin/articles') !!}"><i
                                class="fa fa-circle-o"></i>{{ trans('menus.article') }}</a></li>
                </ul>
            </li>

            <li class="{{ Active::pattern('admin/dashboard') }}">
                <a href=""><span>商城设置</span><i
                        class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{!!url('admin/mall/setting')!!}"><i class="fa fa-circle-o"></i>基础设置</a></li>
                    <li><a href="{!!url('admin/setting/frontpage')!!}"><i class="fa fa-circle-o"></i>首页设置</a></li>
                </ul>
            </li>

            @permission('view-access-management')
            <li class="{{ Active::pattern('admin/access/*') }}"><a
                    href="{!!url('admin/access/users')!!}"><span>{{ trans('menus.access_management') }}</span></a></li>
            @endauth

            <li class="{{ Active::pattern('admin/log-viewer*') }} treeview">
                <a href="#">
                    <span>{{ trans('menus.log-viewer.main') }}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {{ Active::pattern('admin/log-viewer*', 'menu-open') }}"
                    style="display: none; {{ Active::pattern('admin/log-viewer*', 'display: block;') }}">
                    <li class="{{ Active::pattern('admin/log-viewer') }}">
                        <a href="{!! url('admin/log-viewer') !!}">{{ trans('menus.log-viewer.dashboard') }}</a>
                    </li>
                    <li class="{{ Active::pattern('admin/log-viewer/logs') }}">
                        <a href="{!! url('admin/log-viewer/logs') !!}">{{ trans('menus.log-viewer.logs') }}</a>
                    </li>
                </ul>
            </li>

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
