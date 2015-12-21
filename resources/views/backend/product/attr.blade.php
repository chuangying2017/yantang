<script type="x-template" id="attr-tpl">
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" @click.prevent="removeAttr()">×</button>
        <div class="row">
            <div class="col-sm-3">
                <label class="control-label">选择属性</label>
            </div>
            <div class="col-sm-4">
                <select class="attrGroup form-control"
                        v-model="selectedAttrId">
                    <option v-bind:value="attr.id" v-for="attr in attributes">
                        [! attr.name !]
                    </option>
                </select>
            </div>
            <div class="col-sm-4">
                <label @click="showValueInput = true" style="cursor: pointer
                ;
                    color: #367fa9
                ;" class="control-label" v-show="attribute">+添加属性值</label>
                <div v-if="showValueInput" class="attr-popup"
                     style="position: absolute;background: #fff; border: 3px solid #367fa9;padding: 10px;width: 300px;left: -100px;top: 40px;border-radius: 5px;box-shadow: 0 0 10px #a6a6a6;z-index: 99999;">
                    <form action="" style="margin: 0;">
                        <input type="text" v-model="searchValue">
                        <button @click.prevent="submitValue($index)">确定</button>
                        <button @click.prevent="clearSubmitValue($index)">取消</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row attr-val-list">
            <div class="col-sm-12">
                <div class="btn-wrap" style="margin-top: 10px;padding: 10px; background: #fff;border-radius: 3px;"
                     v-show="values.length">
                    <div class="btn-group" v-for="value in values">
                        <button type="button" class="btn btn-xs btn-primary">[! value.name !]</button>
                        <button type="button" class="btn btn-xs btn-primary" @click=" removeValue(value)">x
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<script>
    Vue.component('attribute', {
        template: "#attr-tpl",
        data: function () {
            return {
                searchValue: "",
                showValueInput: false,
                attributes: app.attributes,
                selectedAttrIndex: 0,
                values: []
            }
        },
        props: ['attribute', 'index'],
        created: function () {
            if (this.attribute.id) {
                this.selectedAttrId = this.attribute.id
                this.values = this.attribute.values
            }
        },
        watch: {
            selectedAttrId: function (newVal) {
                _.map(this.attributes, function (val, key) {
                    if (newVal == val.id) {
                        this.attribute = {
                            id: val.id,
                            name: val.name,
                            values: []
                        }
                        this.values = []
                    }
                })
            },
            values: function (newVal) {
                this.attribute.values = newVal;
            }
        },
        methods: {
            submitValue: function () {
                //1. get from api
                //2. check duplicate
                var self = this;
                this.$http.post(app.config.base_url + '/api/admin/attributes/' + this.attribute.id + '/values?_token=' + app.token, {
                    value: this.searchValue
                }, function (data) {
                    var value = data.data
                    self.values.push({
                        id: value.id,
                        name: value.value,
                    });
                })


                this.clearSubmitValue();
            },
            clearSubmitValue: function () {
                this.showValueInput = false;
                this.searchValue = "";
            },
            removeValue: function (value) {
                this.values.$remove(value);
            },
            removeAttr: function () {
                this.$dispatch('attrDeleted', this.index);
            }
        }
    });
</script>
