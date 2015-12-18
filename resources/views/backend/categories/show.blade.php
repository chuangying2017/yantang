@extends('backend.layouts.master')

@section('page-header')
    <h1>
        创建类目
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
                <form class="form-horizontal" action="{{url('admin/categories/' . $category->id)}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="c-red">*</span> 类目名称：</label>
                        <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" required value="{{$category->name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="c-red">*</span> 父级分类：</label>
                        <div class="col-sm-5">
                            <select name="pid" id="" class="form-control" required disabled="disabled">
                                <option value="null">无父级分类</option>
                                @foreach($categories as $child)
                                    @if($category->pid == $child->id)
                                        <option value="{{$child->id}}" selected>{{$child->name}}</option>
                                    @else
                                        <option value="{{$child->id}}">{{$child->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span> 上传封面图：</label>
                        <div class="col-sm-5">
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
@endsection
