<script type="x-template" id="section-tpl">
    <div class="products-group">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label"><span class="c-red">*</span> 名称：</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" v-model="section.title">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><span class="c-red">*</span> 排版：</label>
                <div class="col-sm-2">
                    <select name="" id="" class="form-control" v-model="section.style">
                        <option value="rexiaochanpin">热销产品</option>
                        <option value="xianshizhekou">限时折扣</option>
                        <option value="zuixindongtai">最新动态</option>
                        <option value="meiribikan">每日必看</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><span class="c-red">*</span> 排序：</label>
                <div class="col-sm-2">
                    <input type="number" class="form-control" v-model="section.index">
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
                        <th width="10%">上传/修改</th>
                        <th width="20%">标题</th>
                        <th width="10%">排序</th>
                        <th width="20%">链接</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="n in style[section.style]['limit']">
                        <td>
                            <div class="home-banner">
                                <div class="img-holder" v-show="!section.products[n]">
                                    请上传
                                </div>
                                <img :src="section.products[n]['cover_image'] + '?imageView2/2/w/50'" alt="">
                            </div>
                        </td>
                        <td>
                            <vue-images limit="1" :model.sync="section.products[n]['cover_image']"></vue-images>
                        </td>
                        <td><input type="text" class="form-control" v-model="section.products[n]['title']"
                                   placeholder="必填">
                        </td>
                        <td><input type="number" class="form-control" v-model="section.products[n]['index']"
                                   placeholder="必填"></td>
                        <td><input type="text" class="form-control" v-model="section.products[n]['url']"
                                   placeholder="必填"></td>
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
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <button class="btn btn-success btn-md" @click.prevent="save()">保存商品组</button>
                <button class="btn btn-danger btn-md" @click.prevent="delete()">删除</button>
            </div>
            <div class="col-sm-1"></div>
        </div>

    </div>
</script>
<script type="x-template" id="sections-tpl">
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
                    <product-section v-for="section in sections" :section="section" :index="$index"></product-section>
                    <button class="btn btn-primary" @click.prevent="addSection()">
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
</script>

<script>
    var base_url = "{{url('/admin/setting/sections')}}"
    var SectionModel = {
        title: "",
        style: "",
        index: 1,
        products: []
    };

    Vue.component('product-section', {
        template: '#section-tpl',
        props: ['section', 'index'],
        data: function () {
            return {
                style: {
                    'rexiaochanpin': {
                        limit: 6
                    },
                    'xianshizhekou': {
                        limit: 6
                    },
                    'zuixindongtai': {
                        limit: 4
                    },
                    'meiribikan': {
                        limit: 8
                    }
                }
            }
        },
        computed: {
            method: function () {
                return this.$get('section.id') ? 'put' : 'post';
            }
        },
        methods: {
            save: function () {
                var data = _.clone(this.$get('section'));
                return this.$dispatch('save', this.$get('index'), data, this.$get('method'));
            },
            delete: function () {
                return this.$dispatch('delete', this.$get('index'));
            }
        }
    });
    Vue.component('product-sections', {
        template: '#sections-tpl',
        props: ['sections'],
        methods: {
            addSection: function () {
                this.sections.push(_.clone(SectionModel));
            }
        },
        events: {
            save: function (index, data, method) {
                var url = data.id ? base_url + '/' + data.id : base_url;
                var self = this;
                this.$http[method](url, data, function (data) {
                    if (method == 'post') {
                        self.sections[index]['id'] = data.id;
                    }
                    alert('保存成功!')
                }).error(function (data) {
                    console.error(data);
                });
            },
            delete: function (index) {
                var section = this.sections[index]
                var self = this;
                if (section.id) {
                    this.$http.delete(base_url + '/' + section.id, function (data) {
                        return self.sections.splice(index, 1);
                    }).error(function (data) {
                        console.error(data)
                    });
                } else {
                    return this.sections.splice(index, 1);
                }
            }
        }
    });
</script>
