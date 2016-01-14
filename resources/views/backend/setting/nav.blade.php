@extends('backend.setting.layout')


@section('name')
    导航
@stop

@section('components')
    <navs :navs="navs"></navs>
    <set-nav :navs="navs"></set-nav>
@stop

@section('vue-scripts')
    @include('backend.setting.components.navs')
    @include('backend.setting.components.subnavs')
    <script>
        app = window.app || {}

        var vm = new Vue({
            el: '#setting',
            data: {
                navs: app.navs || []
            }
        })
    </script>
@stop
