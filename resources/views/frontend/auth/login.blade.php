@extends('frontend.layouts.master')

@section('before-styles-end')
<style>
    html, body{
        background: #ddd !important;
    }
</style>
@stop

@section('content')
    <div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html">
            <img src="http://7xp47i.com1.z0.glb.clouddn.com/dflr-logo.png" alt="" style="width: 200px;heigth: auto;">
        </a>
      </div>
      <!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">输入帐号邮箱和密码进行登入</p>
        
        {!! Form::open(['url' => 'auth/login', 'role' => 'form']) !!}
        <!-- <form action="auth/login" method="post"> -->
          {!! csrf_field() !!}
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="登录邮箱" name="email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="登录密码" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> 记住密码
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">登入</button>
            </div>
            <!-- /.col -->
          </div>
        <!-- </form> -->
        {!! Form::close() !!}
        <!-- /.social-auth-links -->

        <a href="password/email">密码忘记了？</a><br>

      </div>
      <!-- /.login-box-body -->
    </div>
    <!-- <div class="row">
    
        <div class="col-md-4 col-md-offset-4">
    
            <div class="panel panel-default">
                <div class="panel-heading">{{trans('labels.login_box_title')}}</div>
    
                <div class="panel-body">
    
                    {!! Form::open(['url' => 'auth/login', 'class' => 'form-horizontal', 'role' => 'form']) !!}
    
                    <div class="form-group">
                        {!! Form::label('email', trans('validation.attributes.email'), ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::input('email', 'email', old('email'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
    
                    <div class="form-group">
                        {!! Form::label('password', trans('validation.attributes.password'), ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
    
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('remember') !!} {{ trans('labels.remember_me') }}
                                </label>
                            </div>
                        </div>
                    </div>
    
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            {!! Form::submit(trans('labels.login_button'), ['class' => 'btn btn-primary', 'style' => 'margin-right:15px']) !!}
    
                            {!! link_to('password/email', trans('labels.forgot_password')) !!}
                        </div>
                    </div>
    
                    {!! Form::close() !!}
    
                    <div class="row text-center">
                        {!! $socialite_links !!}
                    </div>
                </div>panel body
    
            </div>panel
    
        </div>col-md-8
    
    </div>row -->

@endsection
