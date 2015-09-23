<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    @yield('header')
    
    <title>{{trans('admin.title')}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{asset('css/amazeui.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin-be.css')}}">


    @yield('style')
</head>

<body>

@include('partials.admin-header')

<div class="am-cf admin-main">
  <!-- sidebar start -->
  @include('partials.admin-sidebar')
  <!-- sidebar end -->

    <div class="admin-content">
        @yield('content')
    </div>
</div>

<script>
    window.rootUrl = '<?php echo url("/"); ?>';
</script>

<script src="{{asset('js/vendor/jquery.min.js')}}"></script>
<script src="{{asset('js/vendor/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/vendor/amazeui.min.js')}}"></script>
<script src="{{asset('js/admin.js')}}"></script>

<script>
  (function($){
    var sideBar = $('.admin-sidebar');
    if (!localStorage.sideBar) {
        localStorage.setItem("sideBar", "unactive");
    }else if(localStorage.sideBar === 'unactive'){
      sideBar.removeClass('active');
    }else if(localStorage.sideBar === 'active'){
      sideBar.addClass('active');
    }
    var sideBar = $('.admin-sidebar');
    $('.burger').click(function(){
      sideBar.toggleClass('active');
      localStorage.sideBar = localStorage.sideBar == 'active' ? 'unactive' : 'active';
    });
  })(jQuery);
</script>

@yield('script')
</body>
</html>
