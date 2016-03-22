<script type="x-template" id="order-sku-template">
    <tr>
        <td width="40%">
            <div style="float: left;width:20%;">
                <img :src="sku.cover_image + '?imageView2/2/w/80'" alt=""
                     class="cover-img">
            </div>
            <div style="float: left;width:80%;">
                <a target="_blank" href="{{url('/admin/orders/')}}/[! order.order_no !]" class="pro-title">[! sku.title
                    !]</a>
            </div>
        </td>
        <td width="10%">
            [! sku.pay_amount/100 | currency '￥'!]
        </td>
        <td width="10%">
            [! sku.quantity !]
        </td>
        {{--<td width="10%">--}}
        {{--暂无--}}
        {{--</td>--}}
        <td width="10%" style="text-align:center;">
            [! order.address.mobile !]<br><br>
            [! order.address.name !]
        </td>
        <td width="10%" style="text-align:center;">
            <div>
                <span v-if="order.children[0]['status'] == 'paid' && order.refund_status == null">待发货</span>
                <span v-if="order.children[0]['status'] == 'paid' && order.refund_status !== null">申请退款中</span>
                <span v-if="order.children[0]['status'] == 'unpaid'">待支付</span>
                <span v-if="order.children[0]['status'] == 'deliver'">已发货</span>
                <span v-if="order.children[0]['status'] == 'done'">已完成</span>
                <span v-if="order.children[0]['status'] == 'refund'">退款中</span>
                <span v-if="order.children[0]['status'] == 'closed'">已关闭</span>
            </div>
            <a style="margin: 5px auto;display: inline-block;" target="_blank"
               href="{{url('/admin/orders/')}}/[! order.order_no !]">查看详情</a>
            <div>
                <button v-if="order.children[0]['status'] == 'paid' && order.refund_status == null" type="button"
                        class="btn btn-success"
                        data-toggle="modal"
                        data-target="#express" @click="ship()">发货
                </button>
                <button v-if="order.children[0]['status'] == 'paid' && order.refund_status !== null" type="button"
                        class="btn btn-success"
                        data-toggle="modal"
                        data-target="#express" @click="ship()">通过
                </button>
            </div>
        </td>

    </tr>
</script>
<script type="x-template" id="order-template">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th colspan="7">[! order.created_at !] | 订单号：[! order.order_no !]</th>
        </tr>
        <tr v-for="sku in order['children'][0]['skus']" is="sku" :sku="sku" :order="order"></tr>

        <tr v-if="order.memo.length > 0">
            <th colspan="7" class="msg">买家留言：[! order.memo !]
            </th>
        </tr>
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
            this.$log('order')
        },
        methods: {
            ship: function () {
                this.$dispatch('openExpressBox', this.order)
            }
        }
    });
</script>
