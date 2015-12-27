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
                        <option value="jiegou" selected>结构</option>
                        <option value="pingpu">平铺</option>
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
                        <th width="30%">标题</th>
                        <th width="10%">排序</th>
                        <th width="20%">链接</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="n in style[section.style]['limit']">
                        <td>
                            <div class="home-banner">
                                <img :src="section.products[n]['cover_image']" alt="">
                                <vue-images limit="1" :model.sync="section.products[n]['cover_image']"></vue-images>
                            </div>
                        </td>
                        <td><input type="text" class="form-control" v-model="section.products[n]['title']"></td>
                        <td><input type="number" class="form-control" v-model="section.products[n]['index']"></td>
                        <td><input type="text" class="form-control" v-model="section.products[n]['url']"></td>
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
    var SectionModel = {
        title: "",
        style: "",
        products: []
    };

    Vue.component('product-section', {
        template: '#section-tpl',
        props: ['section', 'index'],
        data: function () {
            return {
                style: {
                    'jiegou': {
                        limit: 4
                    },
                    'pingpu': {
                        limit: 3
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
        data: function () {
            return {
                base_url: '/admin/setting/frontpage/sections/'
            }
        },
        methods: {
            addSection: function () {
                this.sections.push(_.clone(SectionModel));
            }
        },
        events: {
            save: function (index, data, method) {
                var url = data.id ? this.$get('base_url') + data.id : this.$get('base_url');
                var self = this;
                this.$http[method](url, data, function (data) {
                    if (method == 'post') {
                        self.sections[index]['id'] = data.id;
                    }
                }).error(function (data) {
                    console.error(data);
                });
            },
            delete: function (index) {
                var section = this.sections[index]
                var self = this;
                if (section.id) {
                    this.$http.delete(this.$get('base_url') + section.id, function (data) {
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
