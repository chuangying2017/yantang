@extends('backend.layouts.master')

@section('page-header')
    <h1>
        文章管理
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
            <a href="" class="btn btn-primary btn-block margin-bottom">创建新的文章</a>
            <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">声明文章</h3>
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="搜索图片名称">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-bordered">
                            <thead>
                            <th>文章标题</th>
                            <th>文章类型</th>
                            <th>创建时间 <i class="fa fa-sort-amount-desc"></i></th>
                            <th>操作</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        东方丽人用户使用协议
                                    </td>
                                    <td>声明文章</td>
                                    <td>2015-09-02 08:02:01</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href=""
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-file"></i> 预览</a>
                                            <a href=""
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-pencil"></i> 编辑</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        东方丽人商户法律法规
                                    </td>
                                    <td>声明文章</td>
                                    <td>2015-09-02 08:02:01</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href=""
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-file"></i> 预览</a>
                                            <a href=""
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-pencil"></i> 编辑</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">博客文章</h3>
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="搜索图片名称">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-bordered">
                            <thead>
                            <th>文章标题</th>
                            <th>文章类型</th>
                            <th>创建时间 <i class="fa fa-sort-amount-desc"></i></th>
                            <th>操作</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        双十一抢购攻略
                                    </td>
                                    <td>博客</td>
                                    <td>2015-09-02 08:02:01</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href=""
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-file"></i> 预览</a>
                                            <a href=""
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-pencil"></i> 编辑</a>
                                            <a href=""
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-trash"></i> 删除</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <div class="box-footer clearfix">
                  <ul class="pagination pagination-sm no-margin pull-right">
                    <li><a href="#">«</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">»</a></li>
                  </ul>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
