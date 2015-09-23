@extends('admin.admin')

@section('content')


    <div class="admin-content">

        <div class="am-cf am-padding">
            <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>数据统计</small></div>
        </div>

        <ul class="am-avg-sm-1 am-avg-md-3 am-margin am-padding am-text-center admin-content-list ">
            <li><a href="#" class="am-text-success"><span class="am-icon-btn am-icon-users"></span><br>授权用户<br>0</a></li>
            <li><a href="#" class="am-text-warning"><span class="am-icon-btn am-icon-briefcase"></span><br>参与人数<br>0</a></li>
            <li><a href="#" class="am-text-danger"><span class="am-icon-btn am-icon-recycle"></span><br>添加信息人数<br>0</a></li>
        </ul>


        </div>

    </div>



@stop