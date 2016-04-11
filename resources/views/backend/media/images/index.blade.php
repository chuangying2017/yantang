@extends('backend.layouts.master')

@section('page-header')
    <h1>
        图片管理
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
            <a href="{{url('/admin/images/action/upload')}}" class="btn btn-primary btn-block margin-bottom">上传新的图片</a>
            <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">图片列表</h3>

                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="mailbox-controls">

                    </div>
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-bordered">
                            <thead>
                            <th>图片</th>
                            <th>图片名称</th>
                            <th>图片格式</th>
                            <th>图片大小</th>
                            <th>上传时间 <i class="fa fa-sort-amount-desc"></i></th>
                            <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($images['data'] as $image)
                                <tr>
                                    <td>
                                        <img width="50" height="50" src="{{$image['url']}}?imageView2/1/w/50/h/50"
                                             alt=""
                                             class="thumb-img">
                                    </td>
                                    <td>{{$image['filename']}}</td>
                                    <td>{{$image['imageinfo']['format']}}</td>
                                    <td>{{$image['imageinfo']['width']}} * {{$image['imageinfo']['height']}}</td>
                                    <td>{{$image['created_at']['date']}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{$image['url']}}"
                                               target="_blank"
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-download"></i> 下载</a>
                                            <a href="{{url('/admin/images/' . $image['id'] . '/delete')}}"
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-trash-o"></i> 删除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        @if($images['meta']['pagination']['current_page'] > 1)
                            <li>
                                <a href="{{url('admin/images')}}?page=1">首页</a>
                            </li>
                            <li>
                                <a href="{{url('admin/images')}}?page={{$images['meta']['pagination']['current_page'] - 1}}">上一页</a>
                            </li>
                        @endif
                        @if($images['meta']['pagination']['current_page'] < $images['meta']['pagination']['total_pages'])
                            <li>
                                <a href="{{url('admin/images')}}?page={{$images['meta']['pagination']['current_page'] + 1}}">下一页</a>
                            </li>
                            <li>
                                <a href="{{url('admin/images')}}?page={{$images['meta']['pagination']['total_pages']}}">末页</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
