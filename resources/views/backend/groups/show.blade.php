@extends('backend.layouts.master')

@section('page-header')
    <h1>
        创建分组
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
                <form class="form-horizontal" action="{{url('/admin/groups/'. $group->id)}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="_method" value="put">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="c-red">*</span> 分组名称：</label>
                        <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" value="{{$group->name}}">
                        </div>
                    </div>
                    {{--<div class="form-group">--}}
                    {{--<label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span> 上传图片：</label>--}}
                    {{--<div class="col-sm-5">--}}
                    {{--<input type="file" value="选择图片上传">--}}
                    {{--<div class="cover-images banner-images">--}}
                    {{--<div class="img-wrapper">--}}
                    {{--<img src="{{$group->group_cover}}" alt="">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<p class="help-block">* 建议上传规格为 900px * 300px 的图片</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                    {{--<label class="col-sm-2 control-label"><span class="c-red">*</span> 选择产品：</label>--}}
                    {{--<div class="col-sm-5">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-sm-5">--}}
                    {{--<div class="form-group">--}}
                    {{--<label>产品列表</label>--}}
                    {{--<select multiple="" class="form-control">--}}
                    {{--<option>产品 1</option>--}}
                    {{--<option>产品 2</option>--}}
                    {{--<option>产品 3</option>--}}
                    {{--<option>产品 4</option>--}}
                    {{--<option>产品 5</option>--}}
                    {{--</select>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-5 col-sm-push-1">--}}
                    {{--<div class="form-group">--}}
                    {{--<label>已选中产品</label>--}}
                    {{--<ul class="groups-select">--}}
                    {{--<li>产品 1</li>--}}
                    {{--<li>产品 2</li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<p class="help-block">* 在产品列表点选产品进行选择：按住 Ctrl 键点选进行多选，点击选中产品进行减选</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="box-footer clearfix">
                        <button type="submit" class="btn btn-primary pull-right">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.col -->
@endsection
