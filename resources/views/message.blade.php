@extends('app')

@section('content')
	<body class="user-create without-prize">
      <img src="{{asset('/img/logo.png')}}" alt="" id="logo">
      <img src="{{asset('/img/header.png')}}" alt="" class="prize-header">
      <div class="prize-body mt40">
        <p class="mb10">
          {!! isset($message) ? $message : '页面出错' !!}
        </p>
        <p class="sub-body">关注九方公众号<br>获得活动最新详情</p>
        <p>
        	<a href="{{route('rules')}}" class="link">抽奖细则</a>
        </p>
      </div>
@stop