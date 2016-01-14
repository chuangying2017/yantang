<script type="x-template" id="navs">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">三级导航</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="20%">一级</th>
                            <th width="20%">二级</th>
                            <th width="20%">选择三级导航</th>
                            <th width="40%">三级菜单（最多15个）</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr is="sub-navs" v-for="nav in navs" :subs="nav"></tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </div>
    </div>
</script>

<script type="x-template" id="subNavs">
    <tr v-for="sub in subs.children">
        <td>
            [! subs.name !]
        </td>
        <td>
            [! sub.name !]
        </td>
        <td is="third-navs" :sub.sync="sub"></td>
    </tr>
</script>

<script type="x-template" id="thirdNavs">
    <td style="position: relative;">
        <input type="text" class="form-control" v-model="selectCat" placeholder="搜索类目并点击添加"
               :disabled="sub.children.length >= 15">
        <ul class="cat-dropdown" v-show="catsActive">
            <li v-for="cat in cats | filterBy selectCat | limitBy 5 " @click="addSubNav(cat.name, cat.id, sub.id)">
            [! cat.name !]
            </li>
        </ul>
    </td>
    <td>
        <div class="btn-group" v-for="thirdNav in sub.children">
            <button class="btn btn-xs btn-primary">[! thirdNav.name !]</button>
            <button class="btn btn-xs btn-primary" @click="deleteSubNav(thirdNav.id, thirdNav);">×</button>
        </div>
    </td>
</script>

<script>
    Vue.component('thirdNavs', {
        template: '#thirdNavs',
        props: ['sub'],
        data: function () {
            return {
                selectCat: "",
                cats: []
            }
        },
        created: function () {
            this.$http.get('/api/admin/categories', function (data) {
                this.$set('cats', data.data);
            }).error(function (data) {
                console.error(data);
            })
        },
        computed: {
            catsActive: function () {
                if (this.selectCat !== "") {
                    return true
                } else {
                    return false
                }
            }
        },
        methods: {
            activeCat: function () {
                this.catsActive = true;
            },
            deactiveCat: function () {
                this.catsActive = false;
            },
            addSubNav: function (name, catId, subId) {
                this.$log('sub');
                var self = this;
                this.$http.post('/admin/setting/navs', {
                    name: name,
                    index: 1,
                    type: 'page',
                    url: catId,
                    pid: parseInt(subId)
                }).then(function (response) {
                    self.selectCat = "";
                    self.sub.children.push(response.data.nav);
                    self.$log('sub');
                }, function (response) {
                    console.log(response.data);
                });
            },
            deleteSubNav: function (id, nav) {
                var self = this;
                this.$http.delete('/admin/setting/navs/' + id, function (data) {
                    self.sub.children.$remove(nav);
                }).error(function (data) {
                    console.error(data);
                })
            }
        }
    })

    Vue.component('subNavs', {
        template: '#subNavs',
        props: ['subs']
    });

    Vue.component('setNav', {
        template: '#navs',
        props: ['navs']
    });
</script>
