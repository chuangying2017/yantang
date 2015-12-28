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
                    <div class="box rwithout-borde">
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
                        {{--<div class="box-footer clearfix">--}}
                        {{--<a type="submit" class="btn btn-primary pull-right" href="#timeline"--}}
                        {{--data-toggle="tab">下一步</a>--}}
                        {{--</div>--}}
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
                                    <label class="col-sm-2 control-label"><span class="c-red">*</span> 所属品牌：</label>
                                    <div class="col-sm-5">
                                        <select name="brand" class="form-control"
                                                v-model="product.brand_id">
                                            <option v-bind:value="brand.id " v-for="brand in brands">[! brand.name !]
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span>
                                        选择分组：</label>
                                    <div class="col-sm-5">
                                        <select name="proGroup" id="proGroup" class="form-control"
                                                v-model="product.group_ids" multiple>
                                            <option v-bind:value="group.id" v-for="group in groups">[! group.name !]
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
                                                   :attribute.sync="product.attributes[$index]"
                                                   :index="$index"
                                                   track-by="$index"></attribute>
                                        <button class="btn btn-primary" type="button" @click="addAttr()">
                                        添加规格项目</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span>
                                        规格库存：</label>
                                    <div class="col-sm-8">
                                        <sku :skus.sync="product.skus.data"
                                             :attributes="product.attributes" :length="product.skus.data.length"></sku>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label">
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
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">商品原价(选填)：</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">￥</span>
                                            <input type="text" class="form-control" placeholder="（单位：元）"
                                                   v-model="product.origin_price">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="proGroup" class="col-sm-2 control-label"><span class="c-red">*</span>
                                        上传图片：</label>
                                    <div class="col-sm-5">
                                        <vue-images limit="4" :model.sync="product.images"></vue-images>
                                        <div class="cover-images">
                                            <div class="img-wrapper" v-for="image in product.images.data">
                                                <span class="cover-tag"
                                                      v-if="image.url == product.cover_image">封面</span>
                                                <span class="set-cover" v-else @click="setCover(image)">设为封面</span>
                                                <span class="remove-img" @click="removeImg(image)">×</span>
                                                <img :src="image.url + '?imageView2/2/w/100'" alt="">
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
                                    <span class="c-red">(0表示不限)</span>
                                    <div class="col-sm-3">
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
                                            <div class="col-sm-6" v-if="product.open_status == 'fixed'">
                                                <input type="text" class="form-control open_status_custom"
                                                       placeholder="yyyy/mm/dd hh:mm:ss"
                                                       v-model="product.open_time">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--<div class="box-footer clearfix">--}}
                        {{--<button type="submit" class="btn btn-primary pull-right" @click="test()">下一步</button>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <!-- /.tab-pane -->

                <div class=" tab-pane" id="settings">
                    <div class="row">
                        <div class="col-md-12">
                            <script id="container" name="content" type="text/plain" style="height:600px;">

                            </script>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
                        <button type="submit" class="btn btn-primary pull-right" @click="save()">保存</button>
                    </div>
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
    {!! HTML::script('js/ueditor/ueditor.config.js') !!}
    {!! HTML::script('js/ueditor/ueditor.all.js') !!}
@endsection

@section('after-scripts-end')
    @include('backend.layouts.vue')
    @include('backend.product.attr')
    @include('backend.product.sku')
    @include('backend.product.gallery')
    <script>
        app = window.app || {}

        // 执行 JS 主逻辑
        var editor = UE.getEditor('container', {});

        var model = {
            category_id: null,
            title: "",
            price: 0,
            origin_price: 0,
            limit: 0,
            cover_image: app.config.default_img,
            open_status: "now",
            attributes: [],
            brand_id: null,
            detail: "test",
            express_fee: 0,
            skus: {
                data: []
            },
            images: {
                data: []
            },
            image_ids: [],
            group_ids: []
        };

        var product = app.product ? app.product.data : model

        var vm = new Vue({
            el: '#app',
            data: {
                method: 'post',
                categories: app.categories,
                groups: app.groups,
                brands: app.brands,
                category: {},
                product: product
            },
            components: ['attribute', 'sku', 'vue-gallery'],
            computed: {
                stock: function () {
                    var stocks = 0;
                    for (var i = 0; i < this.product.skus.data.length; i++) {
                        stocks = stocks + parseInt(this.product.skus['data'][i]['stock']);
                    }
                    return stocks;
                }
            },
            created: function () {
                this.$log('product')
                var self = this;

                if (this.product.id) {
                    this.method = "put";
                    _.map(this.categories, function (val, key) {
                        if (val.id == self.product.category_id) {
                            self.category = val;
                        }
                    })
                }

                editor.ready(function () {
                    UE.commands['gallery'] = {
                        execCommand: function () {
                            self.gallery('setEditorContent');
                        }
                    }
                    editor.setContent(self.$get('product.detail'));
                    editor.addListener('contentchange', function () {
                        self.$set('product.detail', editor.getContent());
                    });
                });
            },
            watch: {
                category: function (newVal) {
                    this.product.category_id = newVal.id;
                },
                'product.images': function (newVal) {
                    var self = this;
                    _.map(newVal.data, function (val, key) {
                        self.product.image_ids.push(val.id)
                    });
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
                save: function () {
                    var data = {
                        data: this.$get('product')
                    }

                    if (this.method == 'post') {
                        this.$http.post(app.config.base_url + '/admin/products?_token=' + app.token, data, function (data) {
                            if (data) {
                                window.location.href = app.config.base_url + '/admin/products';
                            }
                        }).error(function (data) {

                        });
                    } else {
                        this.$http.put(app.config.base_url + '/admin/products/' + this.product.id + '?_token=' + app.token, data, function (data) {
                            if (data) {
                                window.location.href = app.config.base_url + '/admin/products';
                            }
                        }).error(function (data) {

                        });
                    }

                },
                gallery: function (fn) {
                    if (fn) {
                        this.$broadcast('galleryOpen', {
                            fn: fn
                        });
                    } else {
                        this.$broadcast('galleryOpen')
                    }
                },
                removeImg: function (image) {
                    console.log(typeof image.id)
                    if (image.url == this.product.cover_image) {
                        this.product.cover_image = app.config.default_img
                    }
                    this.product.images.data.$remove(image)
                    this.product.image_ids.$remove(image.id.toString())
                },
                setEditorContent: function (data) {
                    _.map(data, function (val, key) {
                        editor.execCommand("insertimage", {
                            src: val.url,
                            width: '100%'
                        })
                    })
                },
                setCover: function (image) {
                    this.product.cover_image = image.url;
                }
            },
            events: {
                attrDeleted: function (index) {
                    this.product.attributes.splice(index, 1);
                },
                gallerySubmit: function (callback) {
                    var self = this;
                    if (callback.method) {
                        this[callback.method](callback.data)
                    }
                },
                'value-change': function () {
                    this.$broadcast('value-change');
                }
            }
        });

        $('.open_status_custom').mask('0000/00/00 00:00:00');
    </script>
@endsection
