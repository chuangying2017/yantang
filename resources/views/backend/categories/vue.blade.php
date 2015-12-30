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
        el: '#category-image',
        created: function () {
            console.log(123123)
        },
        components: ['vue-images'],
        data: {
            category: {
                cover_image: "http://7xpdx2.com2.z0.glb.qiniucdn.com/default.jpeg?imageView2/1/w/100"
            }
        }
    })
</script>
