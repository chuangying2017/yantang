<script type="x-template" id="order-sku-template">
    <tr>
        <td width="40%">
            <img :src="sku.cover_image" alt=""
                 class="cover-img">
            <a href="" class="pro-title">[! sku.title !]</a>
        </td>
        <td width="10%">
            [! sku.price/100 | currency '￥' !]/[! sku.quantity !]件
        </td>
        {{--<td width="10%">--}}
        {{--暂无--}}
        {{--</td>--}}
        <td width="10%">
            13246665701<br>
            林威翰
        </td>
        <td width="10%">
            [! order.created_at !]
        </td>
        <td width="10%">
            <span v-if="order.status == 'paid'">已支付</span>
            <span v-if="order.status == 'unpaid'">待支付</span>
        </td>
        <td width="10%">
            [! sku.pay_amount/100 | currency '￥'!]
        </td>
    </tr>
</script>
<script type="x-template" id="order-template">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th colspan="7">订单号：[! order.order_no !]
                <div class="pull-right">
                    <a href="{{url('/admin/orders/')}}/[! order.order_no !]">查看详情</a>
                    {{---<a href="" title="">备注</a>--}}
                    {{---<a href="" title="">加星</a>--}}
                    <button v-if="order.status == 'paid'" type="button" class="btn btn-xs btn-success"
                            data-toggle="modal"
                            data-target="#express" @click="ship()">发货
                    </button>
                </div>
            </th>
        </tr>
        <tr v-for="sku in order['children'][0]['skus']" is="sku" :sku="sku" :order="order"></tr>

        {{--<tr>--}}
        {{--<th colspan="7" class="msg">买家留言：请发顺丰谢谢！--}}
        {{--</th>--}}
        {{--</tr>--}}
        </tbody>
    </table>
</script>
<script>
    Vue.component('sku', {
        template: '#order-sku-template',
        props: ['sku', 'order'],
        ready: function () {
        }
    });
    Vue.component('order', {
        template: '#order-template',
        props: ['order'],
        ready: function () {
        },
        methods: {
            ship: function () {
                this.$dispatch('openExpressBox', this.order)
            }
        }
    });
</script>
