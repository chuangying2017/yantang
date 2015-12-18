@extends('backend.layouts.master')

@section('after-styles-end')
    {!! HTML::style('js/vendor/ueditor/dist/utf8-php/themes/default/css/ueditor.min.css') !!}
@endsection

@section('page-header')
    <h1>
        新建文章
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
        <!-- /.col -->
        <section class="invoice">
            <input type="text" class="form-control input-lg" placeholder="文章标题">
            <hr>
            <script id="container" name="content" type="text/plain">
                这里写你的初始化内容
            </script>
        </section>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('before-scripts-end')
    {!! HTML::script('js/vendor/ueditor/ueditor.config.js') !!}
    {!! HTML::script('js/vendor/ueditor/ueditor.all.js') !!}
@endsection

@section('after-scripts-end')
    <script type="text/javascript">
        var ue = UE.getEditor('container');
    </script>
@endsection
