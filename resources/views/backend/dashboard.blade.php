@extends('backend.layouts.master')

@section('page-header')
    <h1>
        首页
        <small>Version 2.0</small>
    </h1>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-bar-chart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">今日销售额(元)</span>
                    <span class="info-box-number">{{$stat_data['today_deal_amount']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-cny"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">总销售额(元)</span>
                    <span class="info-box-number">{{$stat_data['total_deal_amount']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user-plus"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">用户总量</span>
                    <span class="info-box-number">{{$stat_data['total_user_count']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">新增用户</span>
                    <span class="info-box-number">{{$stat_data['today_user_count']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
            <!-- TABLE: LATEST ORDERS -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">最新订单</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>商品</th>
                                <th>状态</th>
                                <th>创建时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders->slice(0,10) as $order)
                                <tr>
                                    <td><a href="pages/examples/invoice.html">{{$order->order_no}}</a></td>
                                    <td>{{$order->title}}</td>
                                    @if($order->status == 'paid')
                                        <td><span class="label label-success">已支付</span></td>
                                    @elseif($order->status == 'unpaid')
                                        <td><span class="label label-danger">未支付</span></td>
                                    @elseif($order->status == 'deliver')
                                        <td><span class="label label-warning">已发货</span></td>
                                    @elseif($order->status == 'done')
                                        <td><span class="label label-info">已完成</span></td>
                                    @endif
                                    <td>
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                                            {{$order->created_at}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <a href="/admin/orders" class="btn btn-sm btn-default btn-flat pull-right">查看所有订单</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
        <!-- /.col -->

        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">最新上架</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @foreach($products->slice(0, 5) as $product)
                            <li class="item">
                                <div class="product-img">
                                    <img src="{{$product->cover_image}}?imageView2/2/w/50/h/50" alt="Product Image">
                                </div>
                                <div class="product-info">
                                    <a href="javascript::;" class="product-title">{{$product->title}}
                                        <span
                                            class="label label-warning pull-right">￥{{$product->price/100}}</span></a>
                        <span class="product-description">
                          {{$product->digest}}
                        </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                    <a href="/admin/products" class="uppercase">查看所有商品</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
        <!-- /.col -->
    </div>
@endsection
