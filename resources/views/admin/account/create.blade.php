@extends('admin.admin')

@section('content')

<div class="am-cf am-padding">
  <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">创建后台账号</strong> / <small></small></div>
</div>

<hr/>

<div class="am-g">
  <div class="am-u-sm-12 am-u-md-8">

    @include('partials.show-errors')
    
    <form action="{{action('AdminAccountController@store')}}" method="POST"  class="am-form am-form-horizontal">

        {!! csrf_field() !!}

        {!! Form::adminInput('username', '用户名：') !!}
        {!! Form::adminInput('password', '填写密码：', 'password') !!}
        {!! Form::adminInput('password_confirmation', '确认密码：', 'password') !!}
        {!! Form::adminSubmit() !!}

    </form>

  </div>
</div>

@stop