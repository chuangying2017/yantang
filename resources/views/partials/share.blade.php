@section('wechat-data')
    <script>
        var dataForWeixin = {
            imgUrl: "",
            title: "{{trans('wechat.title')}}",
            timelineTitle: "{{trans('wechat.timelineTitle')}}",
            link: "",
            desc: "{{trans('wechat.desc')}}",
            success: function(res) {},
            cancel: function(res){}
        };
    </script>
@stop