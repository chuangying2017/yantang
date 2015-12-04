<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>{!! trans('admin.title') !!}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta name="renderer" content="webkit">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <link rel="stylesheet" href="{{asset('css/amazeui.min.css')}}"/>

        <style>
            .header {
                text-align: center;
            }
            .header h1 {
                font-size: 200%;
                color: #333;
                margin-top: 30px;
            }
            .header p {
                font-size: 14px;
            }
        </style>

    </head>

    <body>
    <div class="header">
        <div class="am-g">
            <h1>{!! trans('admin.login_title') !!}</h1>
            <p>管理员登陆</p>
        </div>
        <hr />
    </div>
    <div class="am-g">
        <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
            <h3>登录</h3>
            <hr>

            @include('partials.show-errors')

            <form class="form-signin am-form" role="form" action="{{route('admin.login.store')}}" method="post">
                <input name="_token" type="hidden" value="{!!csrf_token()!!}"/>
                <label for="username">账号:</label>
                <input type="text" class="form-control" placeholder="账号" name="username" required autofocus id="username">
                <br>
                <label for="password">密码:</label>
                <input type="password" class="form-control" placeholder="密码" name="password" id="password" required>
                <br>
                <div class="am-cf">
                    <input type="submit" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm am-fl">
                </div>
            </form>
            <hr>
            <p>© 2015 Weazm, Inc. Licensed under MIT license.</p>
        </div>
    </div>

    </body>
</html>



