@extends('backend.layouts.master')

@section('page-header')
    <h1>
        商家列表
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-2">
            <a href="{!! url('/admin/merchants/create') !!}" class="btn btn-primary btn-block margin-bottom">创建新的商家</a>
            <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">分组列表</h3>

                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search Mail">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="mailbox-controls">

                    </div>
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <thead>
                            <th></th>
                            <th>商家名称</th>
                            <th>负责人</th>
                            <th>联系方式</th>
                            <th>商品数目</th>
                            <th>创建时间</th>
                            <th>操作</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>东方丽人</td>
                                <td>Andy</td>
                                <td>13246665701</td>
                                <td>100</td>
                                <td>2015-11-23 20:46:53</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm"><i
                                                class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn btn-default btn-sm"><i
                                                class="fa fa-trash-o"></i></button>
                                        <button type="button" class="btn btn-default btn-sm"><i
                                                class="fa fa-lock"></i> 锁定用户
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm"><i
                                                class="fa fa-sort-numeric-asc"></i> 重置密码
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
