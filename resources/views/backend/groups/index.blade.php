@extends('backend.layouts.master')

@section('page-header')
    <h1>
        商品分组列表
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
            <a href="{!!route('admin.groups.create')!!}" class="btn btn-primary btn-block margin-bottom">创建新的分组</a>
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
                            <th>分组名称</th>
                            <th>组内商品数目</th>
                            <th>创建时间</th>
                            <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td>{{$group->name}}</td>
                                    <td>3</td>
                                    <td>{{$group->created_at}}</td>
                                    <td>
                                        <a href="{{url('/admin/groups/' . $group->id)}}"
                                           class="btn btn-default btn-sm"><i
                                                class="fa fa-pencil"></i></a>
                                        <form style="display: inline-block"
                                              action="{{url('/admin/groups/' . $group->id)}}" ,
                                              method="post">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <input type="hidden" name="_method" value="delete">
                                            <button type="submit" class="btn btn-default btn-sm"><i
                                                    class="fa fa-trash-o"></i></button>
                                        </form>
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
                <div class="box-footer no-padding">
                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
