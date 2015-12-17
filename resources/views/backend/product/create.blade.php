@extends('backend.layouts.master')

@section('page-header')
    <h1>
        创建产品
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

<!-- 加载插件 CSS 文件 -->
@section('before-styles-end')
    {!! HTML::style('js/vendor/select2/dist/css/select2.min.css') !!}
@endsection

@section('content')
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">1. 选择商品类目</a></li>
                <li><a href="#timeline" data-toggle="tab">2. 编辑基本信息</a></li>
                <li><a href="#settings" data-toggle="tab">3. 编辑商品详情</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="activity">
                    <div class="box without-border">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select1">
                                    <label for="select1">彩妆</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select2">
                                    <label for="select2">护肤</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select3">
                                    <label for="select3">防晒</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select4">
                                    <label for="select4">卸妆</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select5">
                                    <label for="select5">粉底</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select6">
                                    <label for="select6">美颜</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select7">
                                    <label for="select7">香水</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="radio" name="category" class="dflr-select" id="select8">
                                    <label for="select8">护肤</label>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="submit" class="btn btn-primary pull-right">下一步</button>
                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                    <div class="box without-border">
                        <div class="box-header with-border">
                            <h3 class="box-title">1. 基本信息</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal">
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 归属类目：</label>
                                <div class="col-sm-5">
                                  <label class="control-label">美妆</label>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span> 选择分组：</label>
                                <div class="col-sm-5">
                                    <select name="proGroup" id="proGroup" class="form-control">
                                        <option selected="true" disabled="disabled">请选择该商品的分组</option>
                                        <option value="1">热门推荐</option>
                                        <option value="2">限时抢购</option>
                                        <option value="3">双十一优惠</option>
                                    </select>
                                </div>
                              </div>
                            </form>
                        </div>
                    </div>
                    <div class="box without-border">
                        <div class="box-header with-border">
                            <h3 class="box-title">2. 库存规格</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal">
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 商品规格：</label>
                                <div class="col-sm-5">
                                    <div class="row attrValueGroup">
                                        <div class="col-sm-4">
                                            <select class="attrGroup form-control">
                                                <option value="1">尺寸</option>
                                                <option value="2">规格</option>
                                                <option value="3">颜色</option>
                                                <option value="4">容量</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12">
                                            <select class="valueGroup form-control" multiple="multiple">
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row attrValueGroup">
                                        <div class="col-sm-4">
                                            <select class="attrGroup form-control">
                                                <option value="1">尺寸</option>
                                                <option value="2">规格</option>
                                                <option value="3">颜色</option>
                                                <option value="4">容量</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12">
                                            <select class="valueGroup form-control" multiple="multiple">
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="button">创建新的商品规格</button>
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span> 规格库存：</label>
                                <div class="col-sm-5">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                              <th style="width: 100px">尺寸</th>
                                              <th style="width: 100px">颜色</th>
                                              <th>价格（元）</th>
                                              <th>库存</th>
                                            </tr>
                                            <tr>
                                              <td rowspan="3">S</td>
                                              <td>蓝色</td>
                                              <td><input type="text" class="form-control"/></td>
                                              <td><input type="text" class="form-control"/></td>
                                            </tr>
                                            <tr>
                                              <td>红色</td>
                                              <td><input type="text" class="form-control"/></td>
                                              <td><input type="text" class="form-control"/></td>
                                            </tr>
                                            <tr>
                                              <td>黄色</td>
                                              <td><input type="text" class="form-control"/></td>
                                              <td><input type="text" class="form-control"/></td>
                                            </tr>
                                            <tr>
                                              <td rowspan="3">L</td>
                                              <td>蓝色</td>
                                              <td><input type="text" class="form-control"/></td>
                                              <td><input type="text" class="form-control"/></td>
                                            </tr>
                                            <tr>
                                              <td>红色</td>
                                              <td><input type="text" class="form-control"/></td>
                                              <td><input type="text" class="form-control"/></td>
                                            </tr>
                                            <tr>
                                              <td>黄色</td>
                                              <td><input type="text" class="form-control"/></td>
                                              <td><input type="text" class="form-control"/></td>
                                            </tr>
                                      </tbody>
                                    </table>
                                </div>
                              </div>
                              <div class="form-group">
                                  <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span> 总库存：</label>
                                  <div class="col-sm-2">
                                    <input type="text" class="form-control">
                                  </div>
                              </div>
                            </form>
                        </div>
                    </div>
                    <div class="box without-border">
                        <div class="box-header with-border">
                            <h3 class="box-title">3. 商品信息</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal">
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 商品名称：</label>
                                <div class="col-sm-5">
                                  <input type="text" class="form-control">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 商品价格：</label>
                                <div class="col-sm-3">
                                  <div class="input-group">
                                    <span class="input-group-addon">￥</span>
                                    <input type="text" class="form-control" placeholder="当前价（单位：元）">
                                  </div>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" placeholder="原价（选填）">
                                </div>
                              </div>
                              <div class="form-group">
                                <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span> 上传图片：</label>
                                <div class="col-sm-5">
                                    <input type="file" value="选择图片上传">
                                    <div class="cover-images">
                                        <div class="img-wrapper">
                                            <img src="http://7xp47i.com1.z0.glb.clouddn.com/grid1-1.jpg" alt="">
                                        </div>
                                        <div class="img-wrapper">
                                            <img src="http://7xp47i.com1.z0.glb.clouddn.com/grid1-1.jpg" alt="">
                                        </div>
                                    </div>
                                    <p class="help-block">* 建议上传规格为 640px * 640px 的图片</p>
                                </div>
                              </div>
                            </form>
                        </div>
                    </div>
                    <div class="box without-border">
                        <div class="box-header with-border">
                            <h3 class="box-title">4. 物流&其他</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal">
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 统一邮费：</label>
                                <div class="col-sm-3">
                                  <div class="input-group">
                                    <span class="input-group-addon">￥</span>
                                    <input type="text" class="form-control" placeholder="（单位：元）">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label">每人限购：</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label">开售时间：</label>
                                <div class="col-sm-5">
                                     <div class="radio">
                                        <label>
                                          <input type="radio" name="open_status" id="open_status_now" value="now" checked="true">
                                          立即开售
                                        </label>
                                      </div>
                                      <div class="row">
                                          <div class="col-sm-3">
                                              <div class="radio">
                                                <label>
                                                  <input type="radio" name="open_status" id="open_status_custom" value="custom">
                                                  定时开售
                                                </label>
                                              </div>
                                          </div>
                                          <div class="col-sm-5">
                                              <input type="text" class="form-control open_status_custom" placeholder="dd/mm/yyyy hh:mm:ss">
                                          </div>
                                      </div>
                                </div>
                              </div>
                            </form>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="submit" class="btn btn-primary pull-right">下一步</button>
                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->

                <div class="active tab-pane" id="settings">
                    <script id="container" name="content" type="text/plain">
                        这里写你的初始化内容
                    </script>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
@endsection

<!-- 加载 JS 插件依赖文件 -->
@section('before-scripts-end')
    {!! HTML::script('js/vendor/select2/dist/js/select2.full.min.js') !!}
    {!! HTML::script('js/vendor/jquery-mask-plugin/dist/jquery.mask.min.js') !!}
    {!! HTML::script('js/vendor/ueditor/dist/utf8-php/ueditor.config.js') !!}
    {!! HTML::script('js/vendor/ueditor/dist/utf8-php/ueditor.all.js') !!}
@endsection

@section('after-scripts-end')
    <script>
        // 执行 JS 主逻辑
        $('.attrGroup').select2({
            tags: true
        });
        $('.valueGroup').select2({
            tags: true
        });
        $('.open_status_custom').mask('00/00/0000 00:00:00');
        var ue = UE.getEditor('container');
    </script>
@endsection
