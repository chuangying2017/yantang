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
    <div class="modal fade express-modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                    <h3 class="box-title">筛选条件</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                   <form class="form-horizontal">
                      <div class="row">
                          <div class="col-sm-1"></div>
                          <div class="col-sm-4">
                              <div class="form-group">
                                <label class="col-sm-4 control-label">订单号：</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control">
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-group">
                                <label class="col-sm-5 control-label">下单时间：</label>
                                <div class="col-sm-7">
                                  <input type="text" class="form-control pull-right" id="reservation">
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-1"></div>
                          <div class="col-sm-4">
                              <div class="form-group">
                                <label class="col-sm-4 control-label">外部单号：</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control">
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-group">
                                <label class="col-sm-5 control-label">订单类型：</label>
                                <div class="col-sm-7">
                                   <select name="" id="" class="form-control">
                                     <option value="">全部订单</option>
                                     <option value="">未支付</option>
                                   </select>
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-group">
                                <label class="col-sm-5 control-label">付款方式：</label>
                                <div class="col-sm-7">
                                   <select name="" id="" class="form-control">
                                     <option value="">网银付款</option>
                                     <option value="">微信支付</option>
                                   </select>
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-1"></div>
                          <div class="col-sm-4">
                              <div class="form-group">
                                <label class="col-sm-4 control-label">收货人姓名：</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control">
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-group">
                                <label class="col-sm-5 control-label">订单状态：</label>
                                <div class="col-sm-7">
                                   <select name="" id="" class="form-control">
                                     <option value="">全部订单</option>
                                   </select>
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-group">
                                <label class="col-sm-5 control-label">物流方式：</label>
                                <div class="col-sm-7">
                                   <select name="" id="" class="form-control">
                                     <option value="">普通物流</option>
                                   </select>
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-1"></div>
                          <div class="col-sm-4">
                              <div class="form-group">
                                <label class="col-sm-4 control-label">收货人手机：</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control">
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-group">
                                <label class="col-sm-5 control-label">微信昵称：</label>
                                <div class="col-sm-7">
                                   <input type="text" class="form-control">
                                </div>
                              </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-group">
                                <label class="col-sm-5 control-label">维权状态：</label>
                                <div class="col-sm-7">
                                   <select name="" id="" class="form-control">
                                     <option value="">全部</option>
                                   </select>
                                </div>
                              </div>
                          </div>
                      </div>
                      
                    </form>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <div class="row">
                          <div class="col-sm-2"></div>
                          <div class="col-sm-8">
                            <button type="submit" class="btn btn-primary">根据条件筛选</button>
                            <button type="submit" class="btn btn-default">批量导出</button>
                            <button type="submit" class="btn btn-default">查看已生成报表</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">全部订单</a></li>
                <li><a href="#timeline" data-toggle="tab">待支付订单</a></li>
                <li><a href="#settings" data-toggle="tab">待发货订单</a></li>
                <li><a href="#settings" data-toggle="tab">已发货订单</a></li>
                <li><a href="#settings" data-toggle="tab">已完成订单</a></li>
                <li><a href="#settings" data-toggle="tab">退款中订单</a></li>
                <li><a href="#settings" data-toggle="tab">已关闭订单</a></li>
            </ul>
            <div class="tab-content order-tables">
                <div class="active tab-pane" id="activity">
                    <table class="table table-bordered">
                      <tbody>
                      <tr>
                        <th width="40%">商品</th>
                        <th width="10%">单价/数量</th>
                        <th width="10%">售后</th>
                        <th width="10%">买家</th>
                        <th width="10%">下单时间</th>
                        <th width="10%">订单状态</th>
                        <th width="10%">实付金额</th>
                      </tr>
                    </tbody></table>

                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <th colspan="7">订单号：E8098093485908982384
                              <div class="pull-right"><a href="">查看详情</a>-<a href="" title="">备注</a>-<a href="" title="">加星</a></div>
                          </th>
                        </tr>
                        <tr>
                          <td width="40%">
                            <img src="http://7xp47i.com1.z0.glb.clouddn.com/grid1-1.jpg" alt="" class="cover-img">
                            <a href="" class="pro-title">巴黎欧莱雅爽身护肤霜</a>
                          </td>
                          <td width="10%">
                            249.00/1件
                          </td>
                          <td width="10%">
                            暂无
                          </td>
                          <td width="10%">
                            13246665701<br>
                            林威翰
                          </td>
                          <td width="10%">
                            2015-09-02<br>
                            23:23:00
                          </td>
                          <td width="10%">
                            已支付
                          </td>
                          <td width="10%">
                            249.00
                          </td>
                        </tr>
                        <tr>
                          <td width="40%">
                            <img src="http://7xp47i.com1.z0.glb.clouddn.com/grid1-1.jpg" alt="" class="cover-img">
                            <a href="" class="pro-title">巴黎欧莱雅爽身护肤霜</a>
                          </td>
                          <td width="10%">
                            249.00/1件
                          </td>
                          <td width="10%">
                            暂无
                          </td>
                          <td width="10%">
                            13246665701<br>
                            林威翰
                          </td>
                          <td width="10%">
                            2015-09-02<br>
                            23:23:00
                          </td>
                          <td width="10%">
                            已支付
                          </td>
                          <td width="10%">
                            249.00
                          </td>
                        </tr>
                        <tr>
                          <th colspan="7" class="msg">买家留言：请发顺丰谢谢！
                          </th>
                        </tr>
                    </tbody></table>

                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <th colspan="7">
                              <span>订单号：E8098093485908982384</span><br>
                              <span>外部单号：E0989238590890458098</span>
                              <span>支付流水号：80982934shj892384sd</span>
                              <div class="pull-right"><a href="">查看详情</a>-<a href="" title="">备注</a>-<a href="" title="">加星</a></div>
                          </th>
                        </tr>
                        <tr>
                          <td width="40%">
                            <img src="http://7xp47i.com1.z0.glb.clouddn.com/grid1-1.jpg" alt="" class="cover-img">
                            <a href="" class="pro-title">巴黎欧莱雅爽身护肤霜</a>
                          </td>
                          <td width="10%">
                            249.00/1件
                          </td>
                          <td width="10%">
                            暂无
                          </td>
                          <td width="10%">
                            13246665701<br>
                            林威翰
                          </td>
                          <td width="10%">
                            2015-09-02<br>
                            23:23:00
                          </td>
                          <td width="10%">
                            已支付<br>
                            <button class="btn btn-default" data-toggle="modal" data-target=".express-modal">发货</button>
                          </td>
                          <td width="10%">
                            249.00
                          </td>
                        </tr>
                    </tbody></table>
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
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
