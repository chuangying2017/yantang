@extends('backend.layouts.master')

@section('page-header')
    <h1>
        产品列表
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <a href="{!!route('admin.products.create')!!}" class="btn btn-primary btn-block margin-bottom">创建商品</a>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Folders</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="{{url('admin/products')}}"><i class="fa fa-trash-o"></i> 出售中的商品</a></li>
                        <li><a href="{{url('admin/products?status=down')}}"><i class="fa fa-trash-o"></i> 下架的商品</a></li>
                        {{--<li><a href="{{url('admin/products')}}"><i class="fa fa-trash-o"></i> 已售罄的商品</a></li>--}}
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->

        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">商品列表</h3>

                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search Mail">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="mailbox-controls">

                    </div>
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <thead>
                            <th>商品ID</th>
                            <th>图片</th>
                            <th>标题</th>
                            <th>价格（元）</th>
                            {{--<th>库存</th>--}}
                            <th>销量</th>
                            <th>创建时间</th>
                            <th>操作</th>
                            </thead>
                            <tbody>

                            @foreach($products as $index => $product)
                                <tr>
                                    <td>{{$product->id}}</td>
                                    <td><img width="50"
                                             src="{{$product->cover_image}}"
                                             alt=""></td>
                                    {{--                                    <td>{{$product->stock}}</td>--}}
                                    <td>{{$product->title}}</td>
                                    <td>{{$product->price/100}}</td>
                                    <td>{{$product->sales}}</td>
                                    <td>{{$product->created_at}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{url('/admin/products/' . $product->id . '/edit')}}"
                                               class="btn btn-default btn-sm"><i
                                                    class="fa fa-pencil"></i></a>
                                            @if($product->status == 'up')
                                                <a href="{{url('/admin/products/' . $product->id . '/operate/down')}}"
                                                   class="btn btn-default btn-sm"><i
                                                        class="fa fa-arrow-circle-down"></i></a>
                                            @else
                                                <a href="{{url('/admin/products/' . $product->id . '/operate/up')}}"
                                                   class="btn btn-default btn-sm"><i
                                                        class="fa fa-arrow-circle-up"></i></a>
                                            @endif
                                            <form style="display: inline-block"
                                                  action="{{url('/admin/products/' . $product->id)}}" ,
                                                  method="post">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" name="_method" value="delete">
                                                <button type="submit" class="btn btn-default btn-sm"><i
                                                        class="fa fa-trash-o"></i></button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                    {!! $products->render() !!}
                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
