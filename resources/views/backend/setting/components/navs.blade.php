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
                <button type="button" @click.prevent="addSubNav()" class="btn btn-default btn-sm"
                        v-show="nav.children.length < 3"><i
                        class="fa fa-list-ul"></i> 添加子导航
                </button>
            </div>
        </td>
    </tr>
    <tr is="sub-nav" v-for="subNav in nav.children" :sub="subNav"></tr>
    <tr v-if="subNavEdit">
        <td colspan="2">
            <p class="sub-nav"> — <input type="text" class="form-control" style="display: inline-block; width: auto;"
                                         v-model="subNavName"></p>
        </td>
        <td>
            <input type="text" class="form-control" v-model="subNavIndex">
        </td>
        <td>
            <select class="form-control">
                <option value="">标题</option>
            </select>
        </td>
        <td>
            <div class="btn-group">
                <button @click.prevent="saveSubNav(nav.id)" type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-save"></i> 保存
                </button>
                <button @click.prevent="cancelSubNav()" type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-remove"></i> 取消
                </button>
            </div>
        </td>
    </tr>
</script>

<script type="x-template" id="sub-nav">
    <tr v-if="editMode">
        <td colspan="2" style="background: #F9F9F9;">
            <p class="sub-nav"><i class="fa fa-list-ul"></i> <input type="text" class="form-control"
                                                                    style="display: inline-block; width: auto;"
                                                                    v-model="sub.name"></p>
        </td>
        <td><input type="text" class="form-control" v-model="sub.index"></td>
        <td>
            <select class="form-control">
                <option value="">标题</option>
            </select>
        </td>
        <td>
            <div class="btn-group">
                <button @click.prevent="save(sub.id);" type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-save"></i> 保存
                </button>
                <button @click.prevent="deactiveEdit();" type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-remove"></i> 取消
                </button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td colspan="2" style="background: #F9F9F9;">
            <p class="sub-nav"><i class="fa fa-list-ul"></i> [! sub.name !]</p>
        </td>
        <td>[! sub.index !]</td>
        <td>[! sub.type !]</td>
        <td>
            <div class="btn-group">
                <button @click.prevent="activeEdit()" type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-pencil"></i> 编辑
                </button>
                <button @click.prevent="delete(sub, sub.id)" type="button" class="btn btn-default btn-sm"><i
                        class="fa fa-trash-o"></i> 删除
                </button>
            </div>
        </td>
    </tr>
</script>

<script>
    Vue.component('subNav', {
        template: '#sub-nav',
        props: ['sub'],
        data: function () {
            return {
                editMode: false
            }
        },
        methods: {
            save: function (subId) {
                var self = this;
                this.$http.put('/admin/setting/navs/' + subId, {
                    name: self.sub.name,
                    index: self.sub.index,
                    type: "page",
                    url: "",
                    pid: parseInt(self.sub.pid)
                }, function (data) {
                    self.editMode = false;
                }).error(function () {
                    console.error(data)
                });
            },
            delete: function (subnav, subId) {
                var self = this;
                this.$http.delete('/admin/setting/navs/' + subId, function (data) {
                    self.$dispatch('remove', subnav);
                }).error(function (data) {
                    // console.error(data);
                });
            },
            activeEdit: function () {
                this.editMode = true;
            },
            deactiveEdit: function () {
                this.editMode = false;
            }
        }
    });

    Vue.component('oneNav', {
        template: '#nav-tpl',
        props: ['nav', 'index'],
        data: function () {
            return {
                editMode: false,
                subNavEdit: false,
                subNavName: "",
                subNavIndex: 1,
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
            },
            addSubNav: function () {
                this.$set('subNavEdit', true);
            },
            cancelSubNav: function () {
                this.$set('subNavEdit', false);
            },
            saveSubNav: function (navId) {
                var self = this;
                this.$http.post('/admin/setting/navs', {
                    name: self.subNavName,
                    index: self.subNavIndex,
                    type: "page",
                    url: "",
                    pid: parseInt(navId)
                }, function (data) {
                    if (!data.nav.children) {
                        data.nav.children = [];
                    }
                    self.nav.children.push(data.nav);
                    self.subNavName = "";
                    self.subNavEdit = false;
                }).error(function (data) {
                    console.log(data);
                });
            }
        },
        events: {
            remove: function (subNav) {
                this.nav.children.$remove(subNav);
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
                    'pid': 0
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
                    'pid': 0
                });
                this.addMode = false;
            },
            cancel: function () {
                this.reset();
            },
            save: function () {
                var self = this;
                var data = _.clone(this.$get('newNav'));
                this.$http.post('/admin/setting/navs', data, function (data) {
                    if (!data.nav.children) {
                        data.nav.children = [];
                    }
                    self.navs.push(data.nav);
                    self.reset();
                }).error(function (data) {
                    console.error(data);
                });
            }
        },
        events: {
            delete: function (index, id) {
                var self = this;
                this.$http.delete('/admin/setting/navs/' + id, function (data) {
                    self.navs.splice(index, 1)
                }).error(function (data) {
                    console.error(data)
                });
            },
            save: function (index, id) {
                var data = this.$get('navs')[index];
                this.$http.put('/admin/setting/navs/' + id, _.clone(data), function (data) {
                }).error(function (data) {
                    console.error(data)
                })
            },
        }
    });
</script>
