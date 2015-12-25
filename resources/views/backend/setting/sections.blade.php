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
                        <option value="jiegou">结构</option>
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
                        <th width="30%">外链</th>
                        <th width="10%">排序</th>
                        <th width="20%">类型</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="n in style[section.style]['limit']">
                        <td>
                            <div class="home-banner">
                                <img src="http://7xp47i.com1.z0.glb.clouddn.com/banner-2.jpg"
                                     alt="">
                            </div>
                        </td>
                        <td><input type="text" class="form-control"
                                   value="http://www.google.com/meizhuang"></td>
                        <td><input type="text" class="form-control" value="1"></td>
                        <td>
                            <select name="" id="" class="form-control">
                                <option value="">类目</option>
                                <option value="">专题</option>
                            </select>
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
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <button class="btn btn-success btn-md">保存商品组</button>
                <button class="btn btn-danger btn-md">删除</button>
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
    }

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
    });
    Vue.component('product-sections', {
        template: '#sections-tpl',
        props: ['sections'],
        created: function () {
        },
        methods: {
            addSection: function () {
                console.log(1231)
                this.sections.push(_.clone(SectionModel));
            }
        }
    });
</script>
