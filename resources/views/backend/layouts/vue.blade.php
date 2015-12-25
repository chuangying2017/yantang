{!! HTML::script('js/vendor/vue/dist/vue.min.js') !!}
{!! HTML::script('js/vendor/vue-resource/dist/vue-resource.min.js') !!}
<script>
    Vue.http.interceptors.push({
        request: function (request) {
            request.params['_token'] = '{{csrf_token()}}';
            return request;
        }
    });
</script>
