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
                            <th>
                                物流公司
                            </th>
                            <th>
                                快递单号
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="express-pros">
                                <a href="" class="pro-title">巴黎欧莱雅爽身护肤霜</a>
                            </td>
                            <td>
                                1
                            </td>
                            <td>
                                暂无
                            </td>
                            <td>
                                暂无
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">收货地址：</label></div>
                            <div class="col-sm-8">深圳市 南山区 留学生创业大厦1311</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">物流公司：</label></div>
                            <div class="col-sm-4">
                                <select name="" id="" class="form-control">
                                    <option selected="true" disabled="disabled">请选择一个物流公司</option>
                                    <option value="">顺丰快递</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-2"><label for="" class="form-label">物流单号：</label></div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary">确认发货</button>
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
                        <li class="active">买家下单<br>2015-09-02 08:09:10</li>
                        <li class="active">成功支付<br>2015-09-02 08:09:10</li>
                        <li>商家发货<br>2015-09-02 08:09:10</li>
                        <li>结算货款<br>2015-09-02 08:09:10</li>
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

                                <p>买家留言：麻烦请发顺丰快递</p>
                            </div>
                        </div>
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <div class="box-footer clearfix">
                    <button type="submit" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target=".express-modal">马上发货
                    </button>
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
