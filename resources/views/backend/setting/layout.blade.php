@extends('backend.layouts.master')

@section('page-header')
    <h1>
        商城配置
        <small>@yield('name')设置</small>
    </h1>
@endsection

@section('after-styles-end')
    <style>
        .sub-nav {
            margin-left: 20px;
        }

        .cat-dropdown {
            list-style-type: none;
            padding: 5px;
            border: 1px solid #ddd;
            position: absolute;
            width: 90%;
            z-index: 100;
            background: #fff;
            box-sizing: border-box;
        }

        .cat-dropdown li {
            cursor: pointer;
            padding: 2px;
        }

        .cat-dropdown li:hover {
            background: #337ab7;
            color: #fff;
        }

        .btn-group {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .btn-group + .btn-group {
            margin-left: 0px;
        }
        .img-holder{
            width: 50px;
            height: 50px;
            background: #ddd;
            color: #A2A2A2;
            font-size: 12px;
            text-align: center;
            line-height: 50px;
        }
    </style>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div id="setting" class="row home-setting">
        <div class="col-md-12">
            @yield('components')
                <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('after-scripts-end')
    @include('backend.layouts.vue')

    @yield('vue-scripts')
@endsection
