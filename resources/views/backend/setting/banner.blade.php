@extends('backend.setting.layout')


@section('name')
    轮播
@stop

@section('components')
    {{--<banners :banners="grids" type="grid"></banners>--}}
    <banners :banners="sliders" type="slider"></banners>
@stop

@section('vue-scripts')
    @include('backend.product.gallery')
    @include('backend.setting.components.banners')
    <script>
        app = window.app || {}

        var vm = new Vue({
            el: '#setting',
            data: {
                grids: app.grids || [],
                sliders: app.sliders || [],
            }
        })
    </script>
@stop
