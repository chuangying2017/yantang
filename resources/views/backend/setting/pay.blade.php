@extends('backend.layouts.master')

@section('page-header')
    <h1>
        商城支付配置
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
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">微信支付</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">微信支付ID：</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" placeholder="Email" value="9879jsdfkl82039480-94-05">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">微信支付密匙：</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputPassword3" placeholder="Password" value="LKJFJLSDo234485908skldjfl-94-05">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">当前状态：</label>
                        <div class="col-sm-4">
                          <label for="inputEmail3" class="control-label">已开通</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-default">关闭微信支付接口</button>
                        </div>
                      </div>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">支付宝支付</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">支付宝商户ID：</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" placeholder="Email" value="9879jsdfkl82039480-94-05" disabled="disabled">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">支付宝支付密匙：</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputPassword3" placeholder="Password" value="LKJFJLSDo234485908skldjfl-94-05" disabled="disabled">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">当前状态：</label>
                        <div class="col-sm-4">
                          <label for="inputEmail3" class="control-label">已关闭</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">开通支付宝支付接口</button>
                        </div>
                      </div>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">网银支付</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">网银商户ID：</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputEmail3" placeholder="Email" value="9879jsdfkl82039480-94-05" disabled="disabled">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">网易支付密匙：</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" id="inputPassword3" placeholder="Password" value="LKJFJLSDo234485908skldjfl-94-05" disabled="disabled">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">当前状态：</label>
                        <div class="col-sm-4">
                          <label for="inputEmail3" class="control-label">已关闭</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">开通网银支付接口</button>
                        </div>
                      </div>
                    </form>
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
