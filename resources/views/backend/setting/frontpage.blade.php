@extends('backend.layouts.master')

@section('page-header')
    <h1>
        商城首页配置
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
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
    @include('backend.setting.navs')
    @include('backend.setting.banners')
    @include('backend.setting.sections')
    @include('backend.product.gallery')
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
