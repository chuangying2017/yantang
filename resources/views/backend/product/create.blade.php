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
                <div class="active tab-pane" id="activity">
                    <div class="box without-border">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2" v-for="cat in categories" v-cloak>
                                    <input type="radio" v-model="category" class="dflr-select"
                                           required
                                           id="select[!cat.id!]"
                                           :value="cat">
                                    <label for="select[!cat.id!]">[! cat.name !]</label>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer clearfix">
                            <a type="submit" class="btn btn-primary pull-right" href="#timeline"
                               data-toggle="tab">下一步</a>
                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class=" tab-pane" id="timeline">
                    <div class="box without-border">
                        <div class="box-header with-border">
                            <h3 class="box-title">1. 基本信息</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span class="c-red">*</span> 归属类目：</label>
                                    <div class="col-sm-5">
                                        <label class="control-label">[! category.name !]</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span>
                                        选择分组：</label>
                                    <div class="col-sm-5">
                                        <select name="proGroup" id="proGroup" class="form-control"
                                                v-model="product.group_ids" multiple>
                                            <option selected="true" disabled="disabled">请选择该商品的分组</option>
                                            <option value="[! group.id !]" v-for="group in groups">[! group.name !]
                                            </option>
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
                                        <attribute v-for="attribute in product.attributes"
                                                   :attribute.sync="product.attributes[$index]" :index="$index"
                                                   track-by="$index"></attribute>
                                        <button class="btn btn-primary" type="button" @click="addAttr()">
                                        添加规格项目</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span>
                                        规格库存：</label>
                                    <div class="col-sm-5">
                                        <sku :skus.sync="product.skus" :attributes="product.attributes"></sku>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span>
                                        总库存：</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" v-model="stock"
                                               disabled="disabled">
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
                                        <input type="text" class="form-control" v-model="product.title">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span class="c-red">*</span> 商品价格：</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">￥</span>
                                            <input type="text" class="form-control" placeholder="当前价（单位：元）"
                                                   v-model="product.price">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" placeholder="原价（选填）"
                                               v-model="product.origin_price">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span>
                                        上传图片：</label>
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
                                            <input type="text" class="form-control" placeholder="（单位：元）"
                                                   v-model="product.express_fee">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">每人限购：</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" v-model="product.limit">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">开售时间：</label>
                                    <div class="col-sm-5">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="open_status" id="open_status_now" value="now"
                                                       checked="true" v-model="product.open_status">
                                                立即开售
                                            </label>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="open_status" id="open_status_custom"
                                                               value="fixed" v-model="product.open_status">
                                                        定时开售
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control open_status_custom"
                                                       placeholder="dd/mm/yyyy hh:mm:ss" v-model="open_time">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="submit" class="btn btn-primary pull-right" @click="test()">下一步</button>
                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->

                <div class=" tab-pane" id="settings">
                    <script id="container" name="content" type="text/plain"> 这里写你的初始化内容 </script>
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
    @include('backend.layouts.vue')
    @include('backend.product.attr')
    @include('backend.product.sku')
    <script>
        app = window.app || {}
        app.uploader = {
            token: "",
            fileNumLimit: 1,
            error: function (file) {
            },
            success: function (file) {
            },
            completed: function (file) {
            }
        }
        // 执行 JS 主逻辑

        $('.open_status_custom').mask('00/00/0000 00:00:00');

        Vue.config.delimiters = ["[!", "!]"];


        new Vue({
            el: '#app',
            data: {
                categories: app.categories,
                groups: app.groups,
                category: {},
                product: {
                    category_id: null,
                    title: "",
                    price: 0,
                    origin_price: 0,
                    limit: 0,
                    cover_image: "",
                    open_status: "now",
                    attributes: [],
                    brand_id: null,
                    detail: "",
                    express_fee: 0,
                    skus: [],
                    image_ids: [],
                    group_ids: []
                }
            },
            components: ['attribute', 'sku'],
            computed: {
                stock: function () {
                    var stocks = 0;
                    for (var i = 0; i < this.product.skus.length; i++) {
                        stocks = stocks + parseInt(this.product.skus[i]['stock']);
                    }
                    return stocks;
                }
            },
            watch: {
                category: function (newVal) {
                    this.product.category_id = newVal.id;
                }
            },
            methods: {
                addAttr: function () {
                    this.product.attributes.push({
                        name: "",
                        id: null,
                        values: []
                    });
                },
                test: function () {
                    this.$log('product')
                }
            },
            events: {
                attrDeleted: function (index) {
                    this.product.attributes.splice(index, 1);
                }
            }
        });

    </script>
@endsection
