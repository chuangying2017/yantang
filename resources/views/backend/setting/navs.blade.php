<script type="x-template" id="navs-tpl">
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
                        <tr is="one-nav" v-for="nav in navs" :index="$index" :nav.sync="nav"></tr>
                        <tr v-if="addMode">
                            <td><input type="text" class="form-control" v-model="newNav.name" required></td>
                            <td><input type="url" class="form-control" v-model="newNav.url" required>
                            </td>
                            <td><input type="number" class="form-control" v-model="newNav.index"></td>
                            <td>
                                <select name="" id="" class="form-control" v-model="newNav.type">
                                    <option value="类目" selected>类目</option>
                                    <option value="页面">页面</option>
                                    <option value="商品">商品</option>
                                </select>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" @click.prevent="save()" class="btn btn-default btn-sm"><i
                                            class="fa fa-save"></i> 保存
                                    </button>
                                    <button type="button" @click="cancel()" class="btn btn-default btn-sm"><i
                                        class="fa fa-remove"></i> 取消
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row" v-if="!addMode">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <button @click.prevent="addMode = true" class="btn btn-primary">
                        新增导航
                    </button>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </div>
    </div>
</script>
<script type="x-template" id="nav-tpl">

    <tr v-if="editMode">
        <td><input type="text" class="form-control" v-model="nav.name" required></td>
        <td><input type="url" class="form-control" v-model="nav.url" required>
        </td>
        <td><input type="number" class="form-control" v-model="nav.index" required></td>
        <td>
            <select name="" id="" class="form-control" v-model="nav.type" required>
                <option value="页面" selected>页面</option>
                <option value="类目">类目</option>
                <option value="商品">商品</option>
            </select>
        </td>
        <td>
            <div class="btn-group">
                <button type="button" @click.prevent="save()" class="btn btn-default btn-sm"><i
                        class="fa fa-save"></i> 保存
                </button>
                <button type="button" @click="cancel()" class="btn btn-default btn-sm"><i
                    class="fa fa-remove"></i> 取消
                </button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td>[! nav.name !]</td>
        <td>[! nav.url !]</td>
        <td>[! nav.index !]</td>
        <td>
            [! nav.type !]
        </td>
        <td>
            <div class="btn-group">
                <button @click.prevent="edit()" type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-pencil"></i> 编辑
                </button>
                <button type="button" @click.prevent="delete()" class="btn btn-default btn-sm"><i
                        class="fa fa-trash-o"></i> 删除
                </button>
            </div>
        </td>
    </tr>
</script>
<script>

    Vue.component('oneNav', {
        template: '#nav-tpl',
        props: ['nav', 'index'],
        data: function () {
            return {
                editMode: false,
                cloneModel: _.clone(this.$get('nav'))
            }
        },
        created: function () {
            console.log(this.index);
        },
        methods: {
            save: function () {
                this.$set('editMode', false);
                this.$dispatch('save', this.index, this.nav.id);
            },
            cancel: function () {
                this.$set('nav', this.$get('cloneModel'));
                this.$set('editMode', false);
            },
            edit: function () {
                this.$set('cloneModel', _.clone(this.$get('nav')))
                this.$set('editMode', true);
            },
            delete: function () {
                console.log('ask for delete')
                this.$dispatch('delete', this.index, this.nav.id);
            }
        }
    });

    Vue.component('navs', {
        template: '#navs-tpl',
        props: ['navs'],
        data: function () {
            return {
                addMode: false,
                newNav: {
                    'name': "",
                    'url': "",
                    'type': "",
                    'index': 1,
                }
            }
        },
        methods: {
            reset: function () {
                this.$set('newNav', {
                    'name': "",
                    'url': "",
                    'type': "",
                    'index': 1,
                });
                this.addMode = false;
            },
            cancel: function () {
                this.reset();
            },
            save: function () {
                var self = this;
                var data = _.clone(this.$get('newNav'));
                this.$http.post('/admin/setting/frontpage/navs', data, function (data) {
                    self.navs.push(data);
                    self.reset();
                }).error(function (data) {
                    console.error(data);
                });
            }
        },
        events: {
            delete: function (index, id) {
                var self = this;
                this.$http.delete('/admin/setting/frontpage/navs/' + id, function (data) {
                    self.navs.splice(index, 1)
                }).error(function (data) {
                    console.error(data)
                });
            },
            save: function (index, id) {
                var data = this.$get('navs')[index];
                this.$http.put('/admin/setting/frontpage/navs/' + id, _.clone(data), function (data) {
                }).error(function (data) {
                    console.error(data)
                })
            },
        }
    });
</script>
