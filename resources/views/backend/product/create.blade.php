@extends('backend.layouts.master')

@section('page-header')
    <h1>
        Laravel 5 Bootstrap
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">1. 选择商品类目</a></li>
                <li><a href="#timeline" data-toggle="tab">2. 编辑基本信息</a></li>
                <li><a href="#settings" data-toggle="tab">3. 编辑商品详情</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="activity">

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="settings">
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
@endsection
