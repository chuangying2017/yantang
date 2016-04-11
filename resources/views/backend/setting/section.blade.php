@extends('backend.setting.layout')


@section('name')
    图片组
@stop

@section('components')
    <product-sections :sections="sections"></product-sections>
@stop

@section('vue-scripts')
    @include('backend.product.gallery')
    @include('backend.setting.components.sections')
    <script>
        app = window.app || {}

        var vm = new Vue({
            el: '#setting',
            data: {
                sections: app.sections || [],
            },
            ready: function () {
                this.$log('sections')
            }
        })
    </script>
@stop
