@extends('backend.layouts.master')

@section('page-header')
    <h1>
        订单列表
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div class="modal fade express-modal" id="express" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">商品发货</h4>
                </div>
                <div class="modal-body order-tables">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>
                                商品
                            </th>
                            <th>
                                数量
                            </th>
                        </tr>
                        <tr v-for="sku in expressOrder['children'][0]['skus']">
                            <td>
                                {{--<input type="checkbox" name="express-pros">--}}
                                <a href="" class="pro-title">[! sku.title !]</a>
                            </td>
                            <td>
                                [! sku.quantity !]
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">订单号：</label></div>
                            <div class="col-sm-8">[! expressOrder.order_no !]</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">物流公司：</label></div>
                            <div class="col-sm-4">
                                <select name="" id="" class="form-control" v-model="express.name">
                                    <option value="default" disabled="disabled">请选择一个物流公司</option>
                                    <option value="[! company.name !]" v-for="company in expressCompanies">[!
                                        company.name
                                        !]
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">物流单号：</label></div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-model="express.post_no">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeExpressBox()">
                    取消</button>
                    <button type="button" class="btn btn-primary" @click="ship()" :disabled="
                    !express.post_no || express.name == 'default'">确认发货</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box-tools pull-right" style="margin:10px;">
                <div class="has-feedback">
                    <form action="?">
                        <label class="text-yellow">(可根据用户名,电话,订单号搜索)</label>
                        <input name="keyword" type="text" class="form-control input-sm"
                               placeholder="关键词" value="">
                        <span type="submit" class="glyphicon glyphicon-search form-control-feedback"></span>
                    </form>
                </div>
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li :class="{active: status == 'paid'}"><a href="?status=paid">待发货</a></li>
                    <li :class="{active: status == 'apply'}"><a href="?status=apply">申请退货</a></li>
                    <li :class="{active: status == 'redeliver'}"><a href="?status=redeliver">退货中</a></li>
                    <li :class="{active: status == 'refunded'}"><a href="?status=refunded">已退款</a></li>
                    <li :class="{active: status == 'done'}"><a href="?status=done">已完成</a></li>
                    <li :class="{active: status == 'closed'}"><a href="?status=closed">已关闭</a></li>
                    <li :class="{active: status == ''}"><a href="?status=">全部订单</a></li>
                </ul>
                <div class="tab-content order-tables">
                    <div class="active tab-pane" id="activity">

                        <div class="no-orders" v-if="orders.length <= 0">
                            暂时没有订单
                        </div>
                        <order v-for="order in orders"
                               :order="order"></order>

                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">

                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /. box -->

            <ul class="pagination" v-if="pagination.total > 1">
                <li :class="{active: pagination.current_page == n + 1 }" v-for="n in pagination.last_page"><a
                        href="?status=[! status !]&page=[! n+1 !]">[! n + 1 !]</a></li>
            </ul>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('after-scripts-end')
    @include('backend.layouts.vue')
    @include('backend.orders.components.order')

    <script>

        var getQuery = function (key) {
            if (!location.search) return false;

            var search = location.search.replace('?', '')
            var arr = search.split('&');
            for (var i = 0; i < arr.length; i++) {
                var queryStr = arr[i]
                var k = queryStr.split('=')[0]
                var v = queryStr.split('=')[1]
                if (k == key) {
                    return v;
                    exit;
                }
            }
            return false;
        }

        new Vue({
            el: "#app",
            data: {
                pagination: app.pagination,
                orders: app.pagination.data,
                status: 'paid',
                expressOrder: {},
                express: {
                    name: 'default',
                    post_no: ""
                },
                expressCompanies: []
            },
            ready: function () {
                if (location.search.indexOf('status') > 0) {
                    this.$set('status', getQuery('status'));
                }
                this.$http.get(app.config.api_url + '/admin/deliver/company', function (data) {
                    this.expressCompanies = data.data
                });
            },
            events: {
                openExpressBox: function (order) {
                    this.expressOrder = order
                }
            },
            methods: {
                closeExpressBox: function () {
                    this.express = {
                        name: "default",
                        post_no: ""
                    }
                    this.expressOrder = {}
                },
                ship: function () {
                    this.$http.post(app.config.api_url + '/admin/deliver/' + this.expressOrder.order_no, this.$get('express'), function (data) {
                        if (data.data) {
                            alert('发货成功!')
                            window.location.reload();
                        }
                    }).error(function (data) {
                        console.error(data)
                    })
                }
            }
        });
    </script>
@endsection
