<script src="http://libs.useso.com/js/jquery/2.0.0/jquery.min.js"></script>
@include('backend.layouts.vue')
@include('backend.product.gallery')
<script>
    app = window.app || {}
    if (!app.config) {
        app.config = {
            api_url: '/api'
        }
    }
    var vm = new Vue({
        el: '#brand-image',
        created: function () {
            console.log(123123)
        },
        components: ['vue-images'],
        data: {
            brand: {
                cover_image: "{{$brand->cover_image}}"
            }
        }
    })
</script>
