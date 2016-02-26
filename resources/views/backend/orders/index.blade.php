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
            {{--<div class="box box-primary">--}}
            {{--<div class="box-header with-border">--}}
            {{--<h3 class="box-title">筛选条件</h3>--}}
            {{--<!-- /.box-tools -->--}}
            {{--</div>--}}
            {{--<!-- /.box-header -->--}}
            {{--<div class="box-body no-padding">--}}
            {{--<form class="form-horizontal">--}}
            {{--<div class="row">--}}
            {{--<div class="col-sm-1"></div>--}}
            {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-4 control-label">订单号：</label>--}}
            {{--<div class="col-sm-8">--}}
            {{--<input type="text" class="form-control">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-5 control-label">下单时间：</label>--}}
            {{--<div class="col-sm-7">--}}
            {{--<input type="text" class="form-control pull-right" id="reservation">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
            {{--<div class="col-sm-1"></div>--}}
            {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-4 control-label">外部单号：</label>--}}
            {{--<div class="col-sm-8">--}}
            {{--<input type="text" class="form-control">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-5 control-label">订单类型：</label>--}}
            {{--<div class="col-sm-7">--}}
            {{--<select name="" id="" class="form-control">--}}
            {{--<option value="">全部订单</option>--}}
            {{--<option value="">未支付</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-5 control-label">付款方式：</label>--}}
            {{--<div class="col-sm-7">--}}
            {{--<select name="" id="" class="form-control">--}}
            {{--<option value="">网银付款</option>--}}
            {{--<option value="">微信支付</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
            {{--<div class="col-sm-1"></div>--}}
            {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-4 control-label">收货人姓名：</label>--}}
            {{--<div class="col-sm-8">--}}
            {{--<input type="text" class="form-control">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-5 control-label">订单状态：</label>--}}
            {{--<div class="col-sm-7">--}}
            {{--<select name="" id="" class="form-control">--}}
            {{--<option value="">全部订单</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-5 control-label">物流方式：</label>--}}
            {{--<div class="col-sm-7">--}}
            {{--<select name="" id="" class="form-control">--}}
            {{--<option value="">普通物流</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
            {{--<div class="col-sm-1"></div>--}}
            {{--<div class="col-sm-4">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-4 control-label">收货人手机：</label>--}}
            {{--<div class="col-sm-8">--}}
            {{--<input type="text" class="form-control">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-5 control-label">微信昵称：</label>--}}
            {{--<div class="col-sm-7">--}}
            {{--<input type="text" class="form-control">--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-3">--}}
            {{--<div class="form-group">--}}
            {{--<label class="col-sm-5 control-label">维权状态：</label>--}}
            {{--<div class="col-sm-7">--}}
            {{--<select name="" id="" class="form-control">--}}
            {{--<option value="">全部</option>--}}
            {{--</select>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--</form>--}}
            {{--<!-- /.mail-box-messages -->--}}
            {{--</div>--}}
            {{--<!-- /.box-body -->--}}
            {{--<div class="box-footer">--}}
            {{--<div class="row">--}}
            {{--<div class="col-sm-1"></div>--}}
            {{--<div class="col-sm-10">--}}
            {{--<div class="row">--}}
            {{--<div class="col-sm-2"></div>--}}
            {{--<div class="col-sm-8">--}}
            {{--<button type="submit" class="btn btn-primary">根据条件筛选</button>--}}
            {{--<button type="submit" class="btn btn-default">批量导出</button>--}}
            {{--<button type="submit" class="btn btn-default">查看已生成报表</button>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            <div class="box-tools pull-right" style="margin:10px;">
                <div class="has-feedback">
                    <form action="?">
                        <input name="keyword" type="text" class="form-control input-sm"
                               placeholder="关键词" value="">
                        <span type="submit" class="glyphicon glyphicon-search form-control-feedback"></span>
                    </form>
                </div>
            </div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li :class="{active: status == ''}"><a href="?">全部订单</a></li>
                    <li :class="{active: status == 'unpaid'}"><a href="?status=unpaid">待支付</a></li>
                    <li :class="{active: status == 'paid'}"><a href="?status=paid">待发货</a></li>
                    {{--<li :class="{active: status == 'deliver'}"><a href="?status=deliver">已发货</a></li>--}}
                    <li :class="{active: status == 'deliver'}"><a href="?status=deliver">已完成</a></li>
                    <li :class="{active: status == 'refund'}"><a href="?status=refund">退款中</a></li>
                    <li :class="{active: status == 'closed'}"><a href="?status=closed">已关闭</a></li>
                </ul>
                <div class="tab-content order-tables">
                    <div class="active tab-pane" id="activity">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th width="40%">商品</th>
                                <th width="10%">单价/数量</th>
                                {{--<th width="10%">售后</th>--}}
                                <th width="10%">买家</th>
                                <th width="10%">下单时间</th>
                                <th width="10%">订单状态</th>
                                <th width="10%">实付金额</th>
                            </tr>
                            </tbody>
                        </table>

                        <order v-for="order in orders | filterBy status in 'children[0].status'" :order="order"></order>

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

            <ul class="pagination">
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
                status: '',
                expressOrder: {},
                express: {
                    name: 'default',
                    post_no: ""
                },
                expressCompanies: []
            },
            ready: function () {
                this.$log('pagination')
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
