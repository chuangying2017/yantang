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
        <td width="20%" style="text-align:center;">
            <div>
                <span
                    v-if="order['status'] == 'paid' && order['children'][0]['status'] !== 'deliver' && order.refund_status == null">待发货</span>
                <span v-if="order['status'] == 'paid' && order.refund_status == 'apply'">申请退款中</span>
                <span v-if="order['status'] == 'paid' && order.refund_status == 'reject'">退款申请被拒绝</span>
                <span v-if="order['status'] == 'unpaid'">待支付</span>
                <span v-if="order['status'] == 'paid' && order['children'][0]['status'] == 'deliver'">已发货</span>
                <span v-if="order['status'] == 'deliver' && order['refund_status'] == 'redeliver'">退货中</span>
                <span v-if="order['status'] == 'done'">已完成</span>
                <span v-if="order['status'] == 'refund'">退款中</span>
                <span v-if="order['status'] == 'closed'">已关闭</span>
            </div>
            <a style="margin: 5px auto;display: inline-block;" target="_blank"
               href="{{url('/admin/orders/')}}/[! order.order_no !]">查看详情</a>
            <div>
                <button v-if="order.children[0]['status'] == 'paid' && order.refund_status == null" type="button"
                        class="btn btn-success"
                        data-toggle="modal"
                        data-target="#express" @click="ship()">发货
                </button>
                <div v-if="order['status'] == 'paid' && order.refund_status == 'apply'">
                    <button type="button"
                            class="btn btn-success" @click="returnAction('approve', order.refund[0]['id'])">通过
                    </button>
                    <button type="button"
                            class="btn btn-danger" @click="returnAction('reject', order.refund[0]['id'])">拒绝
                    </button>
                </div>
                <div v-if="order['status'] == 'deliver' && order.refund_status == 'redeliver'">
                    <button type="button"
                            class="btn btn-success" @click="refund(order.refund[0]['id'])">已收到商品
                    </button>
                </div>
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
        <tr v-if="order.refund[0]['client_memo'].length > 0">
            <th colspan="7" class="msg">退货原因：[! order.refund[0]['client_memo'] !]
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
        },
        methods: {
            returnAction: function (action, order_id) {
                var data = {
                    action: action,
                    memo: ''
                }
                if (action == 'approve') {
                    if (!confirm('确定通过改退货申请?')) {
                        return
                    }
                } else if (action == 'reject') {
                    data.memo = prompt('请输入拒回的原因')
                }

                this.$http.put(app.config.api_url + '/admin/orders/refund/' + order_id, data, function (data) {
                    alert('操作成功!')
                    location.reload()
                }).error(function (data) {
                    console.error(data)
                })
            },
            refund: function (order_id) {
                if (confirm('确认已收到退货商品, 费用退回给用户?')) {
                    this.$http.post(app.config.api_url + '/admin/orders/refund/' + order_id + '/done', function (data) {
                        alert('操作成功!')
                        location.reload()
                    }).error(function (data) {
                        console.error(data)
                    })
                }
            },
            ship: function () {
                this.$dispatch('openExpressBox', this.order)
            }
        }
    });
    Vue.component('order', {
        template: '#order-template',
        props: ['order'],
        ready: function () {
        }
    });
</script>
