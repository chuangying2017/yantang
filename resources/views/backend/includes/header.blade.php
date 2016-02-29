<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{!!route('backend.dashboard')!!}" class="logo"><b>东方丽人后台</b></a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('labels.toggle_navigation') }}</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                {{--<li class="dropdown">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"--}}
                {{--aria-expanded="false">{{ trans('menus.language-picker.language') }} <span--}}
                {{--class="caret"></span></a>--}}
                {{--<ul class="dropdown-menu" role="menu">--}}
                {{--<li>{!! link_to('lang/en', trans('menus.language-picker.langs.en')) !!}</li>--}}
                {{--<li>{!! link_to('lang/zh-CN', trans('menus.language-picker.langs.zh-CN')) !!}</li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                    <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    @if(isset($delivers))
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{{$delivers}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            @if($delivers > 0)
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <ul class="menu">
                                        <li><!-- start notification -->
                                            <a href="#">
                                                <i class="fa fa-reorder text-aqua"></i>有{{$delivers}}个待发货订单
                                            </a>
                                        </li><!-- end notification -->
                                    </ul>
                                </li>
                                <li class="footer"><a
                                        href="/admin/orders?status=paid">{{ trans('strings.see_all.notifications') }}</a>
                                </li>
                            @else
                                <li class="header">暂时没有新的发货订单</li>
                            @endif

                        </ul>
                    @endif
                </li>
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{!! access()->user()->picture !!}" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ access()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{!! access()->user()->picture !!}" class="img-circle" alt="User Image"/>

                            <p>
                                {{ access()->user()->name }} - {{ trans('roles.web_developer') }}
                                <small>{{ trans('strings.member_since') }} XX/XX/XXXX</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Link</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Link</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Link</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">{{ trans('navs.button') }}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{!!url('auth/logout')!!}"
                                   class="btn btn-default btn-flat">{{ trans('navs.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
