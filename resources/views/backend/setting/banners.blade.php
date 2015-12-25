<script type="x-template" id="banner-item-tpl">
    <tr v-if="!editMode">
        <td>
            <div class="home-slider">
                <img src="http://7xp47i.com1.z0.glb.clouddn.com/hero-slide.jpg" alt=""
                     class="home-slider">
            </div>
        </td>
        <td>[! banner.url !]</td>
        <td>[! banner.title !]</td>
        <td>[! banner.index !]</td>
        <td>
            <div class="btn-group">
                <button type="button" @click.prevent="edit()" class="btn btn-default btn-sm"><i
                        class="fa fa-pencil"></i> 编辑
                </button>
                <button type="button" @click.prevent="delete()" class="btn btn-default btn-sm"><i
                        class="fa fa-trash-o"></i> 删除
                </button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td>
            <div class="home-slider">
                <img src="http://7xp47i.com1.z0.glb.clouddn.com/hero-slide3.jpg" alt="">
            </div>
            <input type="file">
        </td>
        <td><input type="text" class="form-control" v-model="banner.url"></td>
        <td><input type="text" class="form-control" v-model="banner.title"></td>
        <td><input type="number" class="form-control" v-model="banner.index"></td>
        <td>
            <div class="btn-group">
                <button type="button" @click.prevent="save()" class="btn btn-default btn-sm"><i
                        class="fa fa-save"></i> 保存
                </button>
                <button type="button" @click.prevent="cancel()" class="btn btn-default btn-sm"><i
                        class="fa fa-remove"></i> 取消
                </button>
            </div>
        </td>
    </tr>
</script>
<script type="x-template" id="banners-tpl">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title" v-if="type == 'slider'">轮播图设置</h3>
            <h3 class="box-title" v-else>导航图设置（需设置4个）</h3>
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
                            <th width="10%">名称</th>
                            <th width="10%">排序</th>
                            <th width="30%">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr is="banner-item" v-for="banner in banners" :banner="banner" :index="$index"></tr>
                        <tr v-if="addMode">
                            <td>
                                <div class="home-slider">
                                    <img src="http://7xp47i.com1.z0.glb.clouddn.com/hero-slide3.jpg" alt="">
                                </div>
                                <input type="file">
                            </td>
                            <td><input type="text" class="form-control" v-model="newBanner.url">
                            <td><input type="text" class="form-control" v-model="newBanner.title">
                            </td>
                            <td><input type="number" class="form-control" v-model="newBanner.index"></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" @click.prevent="save()" class="btn btn-default btn-sm"><i
                                            class="fa fa-save"></i> 保存
                                    </button>
                                    <button type="button" @click.prevent="cancel()" class="btn btn-default btn-sm"><i
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
            <div class="row" v-if="!addMode && banners.length < 4">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <button class="btn btn-primary" @click.prevent="addMode = true">
                        新增轮播图
                    </button>
                    <p class="help-block">* 建议上传规格为 640px * 640px 的图片</p>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </div>
    </div>
</script>
<script>
    Vue.component('bannerItem', {
        template: '#banner-item-tpl',
        props: ['banner', 'index'],
        data: function () {
            return {
                editMode: false,
                cloneModel: _.clone(this.$get('banner'))
            }
        },
        created: function () {
            console.log(this.index);
        },
        methods: {
            save: function () {
                this.$set('editMode', false);
                this.$dispatch('save', this.index, this.banner.id);
            },
            cancel: function () {
                this.$set('banner', this.$get('cloneModel'));
                this.$set('editMode', false);
            },
            edit: function () {
                this.$set('cloneModel', _.clone(this.$get('banner')))
                this.$set('editMode', true);
            },
            delete: function () {
                this.$dispatch('delete', this.index, this.banner.id);
            }
        }
    });

    Vue.component('banners', {
        template: '#banners-tpl',
        props: ['banners', 'type'],
        data: function () {
            return {
                addMode: false,
                newBanner: {
                    'title': "",
                    'url': "",
                    'cover_image': "http://7xp47i.com1.z0.glb.clouddn.com/hero-slide3.jpg",
                    'type': this.$get('type'),
                    'index': 1,
                }
            }
        },
        methods: {
            reset: function () {
                this.$set('newBanner', {
                    'title': "",
                    'url': "",
                    'cover_image': "http://7xp47i.com1.z0.glb.clouddn.com/hero-slide3.jpg",
                    'type': this.$get('type'),
                    'index': 1,
                });
                this.addMode = false;
            },
            cancel: function () {
                this.reset();
            },
            save: function () {
                var self = this;
                var data = _.clone(this.$get('newBanner'));
                this.$http.post('/admin/setting/frontpage/banners', data, function (data) {
                    self.banners.push(data.data);
                    self.reset();
                }).error(function (data) {
                    console.error(data);
                });
            }
        },
        events: {
            delete: function (index, id) {
                var self = this;
                this.$http.delete('/admin/setting/frontpage/banners/' + id, function (data) {
                    self.banners.splice(index, 1)
                }).error(function (data) {
                    console.error(data)
                });
            },
            save: function (index, id) {
                var data = this.$get('banners')[index];
                this.$http.put('/admin/setting/frontpage/banners/' + id, _.clone(data), function (data) {
                }).error(function (data) {
                    console.error(data)
                })
            },
        }
    });
</script>
