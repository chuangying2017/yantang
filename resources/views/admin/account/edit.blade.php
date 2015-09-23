@extends('admin.admin')


@section('content')

<div class="am-cf am-padding">
  <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">编辑商家后台账号</strong> / <small>商家：{{$admin['client']['name']}}</small></div>
</div>

<hr/>

<div class="am-g">
  <div class="am-u-sm-12 am-u-md-8">

    @include('partials.show-errors')
    
    <form action="{{route('admin.account.update', [$admin['id']])}}" method="POST"  class="am-form am-form-horizontal">
        
        {!! csrf_field() !!}
        <input type="hidden" name="_method" value="PUT">

        {!! Form::adminInputEdit($admin, 'username', '用户名：') !!}
        @if($auth_admin['id'] == $admin['id'] || $auth_admin['role'] != 'super')
            {!! Form::adminInput('origin_password', '原密码：', 'password') !!}
        @endif
        {!! Form::adminInput('password', '修改密码：', 'password') !!}
        {!! Form::adminInput('password_confirmation', '确认密码：', 'password') !!}
        {!! Form::adminSubmit() !!}

    </form>

  </div>
</div>

@stop