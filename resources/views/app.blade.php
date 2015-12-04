<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @yield('header')
    <title>{!! trans('app.title') !!}</title>
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('') }}{{ elixir('css/app.css') }}">
    @yield('style')
</head>
<body>
@yield('content')

<script
        src="//cdn.weazm.com/bugsnag-2.min.js"
        data-apikey="{!! env('BUGSNAG_FRONTEND_API_KEY', '') !!}">
</script>

@if(isset($signPackage))

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        var dataForWeixin = {
            imgUrl: "{!! trans('wechat.img') !!}",
            title: "{{trans('wechat.title')}}",
            timelineTitle: "{{trans('wechat.timelineTitle')}}",
            link: "{!! route('home') !!}",
            desc: "{{trans('wechat.desc')}}",
            success: function(res) {},
            cancel: function(res){}
        };
    </script>

    @yield('wechat-data')

    <script>
        wx.config({
            appId: '<?php echo $signPackage["appid"];?>',
            timestamp: '<?php echo $signPackage["timestamp"];?>',
            nonceStr: '<?php echo $signPackage["noncestr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: [
                'onMenuShareTimeline',
                'hideMenuItems',
                'showMenuItems',
                'onMenuShareAppMessage',
                'hideOptionMenu',
                'showOptionMenu',
                'closeWindow'
            ]
        });

        wx.ready(function(){
            wx.showOptionMenu();
            wx.onMenuShareTimeline({
                title: dataForWeixin.timelineTitle,
                link: dataForWeixin.link,
                imgUrl: dataForWeixin.imgUrl,
                success: dataForWeixin.success,
                cancel: dataForWeixin.cancel
            });
            wx.onMenuShareAppMessage({
                title: dataForWeixin.title,
                link: dataForWeixin.link,
                desc: dataForWeixin.desc,
                imgUrl: dataForWeixin.imgUrl,
                success: dataForWeixin.success,
                cancel: dataForWeixin.cancel
            });
        });
    </script>

@endif

@yield('script')




</body>
</html>
