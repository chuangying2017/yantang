@extends('backend.layouts.master')

@section('page-header')
    <h1>
        创建品牌
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
    @endsection

        <!-- 加载插件 CSS 文件 -->
@section('before-styles-end')
    {!! HTML::style('js/vendor/select2/dist/css/select2.min.css') !!}
@endsection

@section('content')
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">1. 基本信息</h3>
            </div>
            <div class="box-body">
                <form class="form-horizontal" action="{{url('admin/brands/' . $brand->id)}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="c-red">*</span> 品牌名称：</label>
                        <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" required value="{{$brand->name}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span> 上传封面图：</label>
                        <div class="col-sm-5">
                            <!--dom结构部分-->
                            <div id="uploader-demo">
                                <!--用来存放item-->
                                <div id="fileList" class="uploader-list"></div>
                                <div id="filePicker">选择图片</div>
                                <button id="uploadBtn">upload</button>
                            </div>

                            <input type="file" value="选择图片上传">
                            <div class="cover-images banner-images">
                                <div class="img-wrapper">
                                    <img src="http://7xp47i.com1.z0.glb.clouddn.com/grid1-1.jpg" alt="">
                                </div>
                            </div>
                            <p class="help-block">* 建议上传规格为 900px * 300px 的图片</p>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
                        <button type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.col -->
    <script>

    </script>
@endsection

@section('after-scripts-end')
    <script>
        app = window.app || {}
        app.uploader = {
            token: "",
            fileNumLimit: 1,
            error: function (file) {
            },
            success: function (file) {
            },
            completed: function (file) {
            }
        }
    </script>
    @include('backend.layouts.upload')
@endsection
