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
                            <th width="15%">一级</th>
                            <th width="15%">二级</th>
                            <th width="10%">类型</th>
                            <th width="20%">选择三级导航</th>
                            <th width="40%">三级菜单（最多15个，浅色为品牌）</th>
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
        <td is="third-navs" :sub.sync="sub" :cats="cats" :brands="brands"></td>
    </tr>
</script>

<script type="x-template" id="thirdNavs">
    <td>
        <select class="form-control" v-model="navType">
            <option value="cat">类目</option>
            <option value="brand">品牌</option>
        </select>
    </td>
    <td style="position: relative;">
        <input type="text" class="form-control" v-model="selectType" placeholder="搜索类目并点击添加"
               :disabled="sub.children.length >= 15">
        <ul class="cat-dropdown" v-show="typesActive">
            <li v-for="type in types | filterBy selectType | limitBy 5 " @click="addSubNav(type.name, type.id, sub.id)">
            [! type.name !]
            </li>
        </ul>
    </td>
    <td>
        <div class="btn-group" v-for="thirdNav in sub.children">
            <button v-bind:class="['btn', 'btn-xs', thirdNav.type == 'brand' ? 'btn-info' : 'btn-primary']">[! thirdNav.name !]</button>
            <button v-bind:class="['btn', 'btn-xs', thirdNav.type == 'brand' ? 'btn-info' : 'btn-primary']" @click="deleteSubNav(thirdNav.id, thirdNav);">×</button>
        </div>
    </td>
</script>

<script>
    Vue.component('thirdNavs', {
        template: '#thirdNavs',
        props: ['sub', 'cats', 'brands'],
        data: function () {
            return {
                selectType: "",
                navType: 'cat'
            }
        },
        computed: {
            typesActive: function () {
                if (this.selectType !== "") {
                    return true
                } else {
                    return false
                }
            },
            types: function(){
                var self = this;
                if(self.navType == 'cat'){
                    return self.cats;
                }else if(self.navType == 'brand'){
                    return self.brands;
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
                    type: self.navType,
                    url: catId,
                    pid: parseInt(subId)
                }).then(function (response) {
                    self.selectType = "";
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
        props: ['subs'],
        data: function(){
            return {
                cats: [],
                brands: []
            }
        },
        created: function(){
            this.$http.get('/api/admin/categories', function (data) {
                this.$set('cats', data.data);
            }).error(function (data) {
                console.error(data);
            });
            this.$http.get('/api/admin/brands', function (data) {
                this.$set('brands', data.data);
            }).error(function (data) {
                console.error(data);
            });
        }
    });

    Vue.component('setNav', {
        template: '#navs',
        props: ['navs']
    });
</script>
