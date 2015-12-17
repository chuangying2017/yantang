@extends('backend.layouts.master')

@section('page-header')
    <h1>
        商城首页配置
        <small>{{ trans('strings.backend.dashboard_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a>
    </li>
    <li class="active">{{ trans('strings.here') }}</li>
@endsection

@section('content')
    <div class="row home-setting">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">导航设置</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th width="20%">名称</th>
                              <th width="30%">外链</th>
                              <th width="10%">排序</th>
                              <th width="10%">类型</th>
                              <th width="30%">操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>护理</td>
                              <td>http://www.google.com/huli</td>
                              <td>2</td>
                              <td>
                                类目
                              </td>
                              <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-pencil"></i> 编辑</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-trash-o"></i> 删除</button>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" value="美妆"></td>
                              <td><input type="text" class="form-control" value="http://www.google.com/meizhuang"></td>
                              <td><input type="text" class="form-control" value="1"></td>
                              <td>
                                <select name="" id="" class="form-control">
                                  <option value="">类目</option>
                                  <option value="">专题</option>
                                </select>
                              </td>
                              <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-remove"></i> 取消</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-save"></i> 保存</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-trash-o"></i> 删除</button>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-sm-1"></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <button class="btn btn-primary">
                          新增导航
                        </button>
                      </div>
                      <div class="col-sm-1"></div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">轮播图设置</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th width="20%">图片</th>
                              <th width="30%">外链</th>
                              <th width="10%">排序</th>
                              <th width="10%">类型</th>
                              <th width="30%">操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <div class="home-slider">
                                  <img src="http://7xp47i.com1.z0.glb.clouddn.com/hero-slide.jpg" alt="" class="home-slider">
                                </div>
                              </td>
                              <td>http://www.google.com/huli</td>
                              <td>2</td>
                              <td>
                                类目
                              </td>
                              <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-pencil"></i> 编辑</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-trash-o"></i> 删除</button>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <div class="home-slider">
                                  <img src="http://7xp47i.com1.z0.glb.clouddn.com/hero-slide3.jpg" alt="">
                                </div>
                                <input type="file">
                              </td>
                              <td><input type="text" class="form-control" value="http://www.google.com/meizhuang"></td>
                              <td><input type="text" class="form-control" value="1"></td>
                              <td>
                                <select name="" id="" class="form-control">
                                  <option value="">类目</option>
                                  <option value="">专题</option>
                                </select>
                              </td>
                              <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-remove"></i> 取消</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-save"></i> 保存</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-trash-o"></i> 删除</button>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-sm-1"></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <button class="btn btn-primary">
                          新增轮播图
                        </button>
                        <p class="help-block">* 建议上传规格为 640px * 640px 的图片</p>
                      </div>
                      <div class="col-sm-1"></div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">导航图设置（需设置4个）</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th width="20%">图片</th>
                              <th width="30%">外链</th>
                              <th width="10%">排序</th>
                              <th width="10%">类型</th>
                              <th width="30%">操作</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <div class="home-banner">
                                  <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                </div>
                              </td>
                              <td>http://www.google.com/huli</td>
                              <td>2</td>
                              <td>
                                类目
                              </td>
                              <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-pencil"></i> 编辑</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-trash-o"></i> 删除</button>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <div class="home-banner">
                                  <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-2.jpg" alt="">
                                </div>
                                <input type="file">
                              </td>
                              <td><input type="text" class="form-control" value="http://www.google.com/meizhuang"></td>
                              <td><input type="text" class="form-control" value="1"></td>
                              <td>
                                <select name="" id="" class="form-control">
                                  <option value="">类目</option>
                                  <option value="">专题</option>
                                </select>
                              </td>
                              <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-remove"></i> 取消</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-save"></i> 保存</button>
                                    <button type="button" class="btn btn-default btn-sm"><i
                                            class="fa fa-trash-o"></i> 删除</button>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-sm-1"></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <button class="btn btn-primary">
                          新增导航图
                        </button>
                        <p class="help-block">* 建议上传规格为 640px * 640px 的图片</p>
                      </div>
                      <div class="col-sm-1"></div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">商品组</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                      <div class="col-sm-1"></div>
                      <div class="col-sm-10">
                        <div class="products-group">
                          <div class="form-horizontal">
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 名称：</label>
                                <div class="col-sm-2">
                                  <input type="text" class="form-control">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 排版：</label>
                                <div class="col-sm-2">
                                  <select name="" id="" class="form-control">
                                    <option value="">结构</option>
                                    <option value="">平铺</option>
                                  </select>
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th width="10%">图片</th>
                                    <th width="30%">外链</th>
                                    <th width="10%">排序</th>
                                    <th width="20%">类型</th>
                                    <th width="30%">操作</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-2.jpg" alt="">
                                      </div>
                                    </td>
                                    <td><input type="text" class="form-control" value="http://www.google.com/meizhuang"></td>
                                    <td><input type="text" class="form-control" value="1"></td>
                                    <td>
                                      <select name="" id="" class="form-control">
                                        <option value="">类目</option>
                                        <option value="">专题</option>
                                      </select>
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-remove"></i> 取消</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-save"></i> 保存</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <div class="col-sm-1"></div>
                          </div>
                          <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                              <p class="help-block">* 建议上传规格为 640px * 640px 的图片</p>
                            </div>
                            <div class="col-sm-1"></div>
                          </div>
                        </div>
                        <div class="products-group">
                          <div class="form-horizontal">
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 名称：</label>
                                <div class="col-sm-2">
                                  <label class="control-label">热卖商品</label> <a href="#">修改</a>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="c-red">*</span> 排版：</label>
                                <div class="col-sm-2">
                                  <label class="control-label">平铺</label> <a href="#">修改</a>
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th width="10%">图片</th>
                                    <th width="30%">外链</th>
                                    <th width="10%">排序</th>
                                    <th width="20%">类型</th>
                                    <th width="30%">操作</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-1.jpg" alt="" class="home-slider">
                                      </div>
                                    </td>
                                    <td>http://www.google.com/huli</td>
                                    <td>2</td>
                                    <td>
                                      类目
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-pencil"></i> 编辑</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div class="home-banner">
                                        <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-2.jpg" alt="">
                                      </div>
                                    </td>
                                    <td><input type="text" class="form-control" value="http://www.google.com/meizhuang"></td>
                                    <td><input type="text" class="form-control" value="1"></td>
                                    <td>
                                      <select name="" id="" class="form-control">
                                        <option value="">类目</option>
                                        <option value="">专题</option>
                                      </select>
                                    </td>
                                    <td>
                                      <div class="btn-group">
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-remove"></i> 取消</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-save"></i> 保存</button>
                                          <button type="button" class="btn btn-default btn-sm"><i
                                                  class="fa fa-trash-o"></i> 删除</button>
                                      </div>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <div class="col-sm-1"></div>
                          </div>
                          <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                              <p class="help-block">* 建议上传规格为 640px * 640px 的图片</p>
                            </div>
                            <div class="col-sm-1"></div>
                          </div>
                        </div>
                        <button class="btn btn-primary">
                          新增商品组
                        </button>
                      </div>
                      <div class="col-sm-1"></div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
