@extends('backend.layouts.master')

@section('page-header')
    <h1>
        商城首页配置
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    <style>
        .sub-nav{
            margin-left: 20px;
        }
        .cat-dropdown{
            list-style-type: none;
            padding: 5px;
            border: 1px solid #ddd;
            position: absolute;
            width: 90%;
            z-index: 100;
            background: #fff;
            box-sizing: border-box;
        }
        .cat-dropdown li{
            cursor: pointer;
            padding: 2px;
        }
        .cat-dropdown li:hover{
            background: #337ab7;
            color: #fff;
        }
        .btn-group {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .btn-group + .btn-group{
            margin-left: 0px;
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
            <navs :navs="navs"></navs>
            <set-nav :navs="navs"></set-nav>
            <banners :banners="grids" type="slider"></banners>
            <banners :banners="sliders" type="grid"></banners>
            <product-sections :sections="sections"></product-sections>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('after-scripts-end')
    @include('backend.layouts.vue')
    @include('backend.product.gallery')
    @include('backend.setting.navs')
    @include('backend.setting.subnavs')
    @include('backend.setting.banners')
    @include('backend.setting.sections')
    <script>
        app = window.app || {}

        var vm = new Vue({
            el: '#setting',
            data: {
                navs: app.navs || [],
                grids: app.grids || [],
                sliders: app.sliders || [],
                sections: app.sections || []
            }
        })
    </script>
@endsection
