@extends('backend.layouts.master')

@section('page-header')
    <h1>
        订单详情
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div class="modal fade express-modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                        @foreach($order->children[0]->skus as $sku)
                            <tr>
                                <td>
                                    <a href="" class="pro-title">{{$sku->title}}</a>
                                </td>
                                <td>
                                    {{$sku->quantity}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">订单号：</label></div>
                            <div class="col-sm-8">{{$order->order_no}}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">物流公司：</label></div>
                            <div class="col-sm-4">
                                <select name="" id="" class="form-control" v-model="express.name">
                                    <option selected="true" disabled="disabled">请选择一个物流公司</option>
                                    @foreach($expressCompanies['data'] as $company)
                                        <option value="{{$company['name']}}">{{$company['name']}}</option>
                                    @endforeach
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
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">订单状态</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul id="progressbar" class="mt10">
                        <li class="active">买家下单<br>{{$order->created_at}}</li>
                        @if($order->status == 'paid')
                            <li class="active">成功支付<br>{{$order->pay_at}}</li>
                        @else
                            <li class="">成功支付<br></li>
                        @endif
                        @if($order->children[0]->status == 'deliver')
                            <li class="active">商家发货<br>{{$order->deliver_at}}</li>
                        @else
                            <li>商家发货<br></li>
                        @endif
                        @if($order->children[0]->status == 'done')
                            <li class="active">结算货款<br>{{$order->done_at}}</li>
                        @else
                            <li>结算货款<br></li>
                        @endif
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">订单信息</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="order-content">
                        <div class="row">
                            <div class="col-sm-5">
                                <h4>订单信息</h4>

                                <p>订单编号：{{$order->order_no}}</p>

                                <p>付款方式：{{$order->pay_type}}</p>
                            </div>
                            <div class="col-sm-7">
                                <h4>发货信息</h4>

                                <p>买家：{{$order->address->name}}</p>

                                <p>
                                    联系方式：{{$order->address->province}} {{$order->address->city}} {{$order->address->district}} {{$order->address->detail}} {{$order->address->mobile}}</p>

                                <p>买家留言：{{$order->memo}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <div class="box-footer clearfix">
                    @if($order->children[0]->status == 'paid')
                        <button type="submit" class="btn btn-primary pull-right" data-toggle="modal"
                                data-target=".express-modal">马上发货
                        </button>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">订单商品</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body order-tables">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th width="40%">商品</th>
                            <th width="10%">单价/数量</th>
                        </tr>
                        @foreach($order->children[0]->skus as $sku)
                            <tr>
                                <td width="40%">
                                    <img src="{{$sku->cover_image}}" alt=""
                                         class="cover-img">
                                    <a href="" class="pro-title">{{$sku->title}}</a>
                                </td>
                                <td width="10%">
                                    {{$sku->price / 100}}/{{$sku->quantity}}件
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /. box -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('after-scripts-end')
    @include('backend.layouts.vue')

    <script>

        new Vue({
            el: "#app",
            data: {
                express: {
                    name: 'default',
                    post_no: ''
                },
                order_no: "{{$order->order_no}}"

            },
            methods: {
                closeExpressBox: function () {
                    this.express = {
                        name: "default",
                        post_no: ""
                    }
                },
                ship: function () {
                    this.$http.post(app.config.api_url + '/admin/deliver/' + this.order_no, this.$get('express'), function (data) {
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
