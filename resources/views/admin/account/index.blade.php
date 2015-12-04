@extends('admin.admin')

@section('content')
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">管理员管理</strong></div>
    </div>

    <div class="am-g mb">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-success" href="{{action('AdminAccountController@create')}}"><span class="am-icon-plus"></span> 新增管理员 </a>
          </div>
        </div>
      </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12">
          <table class="am-table am-table-bd am-table-striped admin-content-table">
            <thead>
            <tr>
              <th>管理员用户名</th>
              <th>管理员操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>{{$admin['username']}}</td>
                    <td>
                        <div class="am-btn-toolbar">
                          <div class="am-btn-group am-btn-group-xs">
                            <a class="am-btn am-btn-default am-btn-xs" href="{{action('AdminAccountController@edit', $admin['id'])}}"><span class="am-icon-pencil-square-o"></span> 编辑用户</a>
                          </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
@stop