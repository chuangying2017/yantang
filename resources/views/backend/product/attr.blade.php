<script type="x-template" id="attr-tpl">
    <div class="row attrValueGroup">
        <a href="" @click.prevent="removeAttr()">delete</a>
        <div class="row">
            <div class="col-sm-4">
                <select class="attrGroup form-control"
                        v-model="attribute">
                    <option value="[! attr !]" v-for="attr in attributes">
                        [! attr.name !]
                    </option>
                </select>
            </div>
        </div>
        <div class="row attr-val-list">
            <div v-if="showValueInput">
                <form action="">
                    <input type="text" v-model="searchValue">
                    <button @click.prevent="submitValue($index)">确定</button>
                    <button @click.prevent="clearSubmitValue($index)">取消</button>
                </form>
            </div>
            <li v-for="value in attribute['values']">
                [! value.name !]
                <a @click=" removeValue(value)">x</a>
            </li>
        </div>
        <div class="col-sm-12">
            <a @click="showValueInput = true">+添加</a>
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
                attributes: app.attributes
            }
        },
        props: ['attribute', 'index'],
        methods: {
            submitValue: function () {
                //1. get from api
                //2. check duplicate
                this.attribute['values'].push({
                    id: 1,
                    name: this.searchValue,
                });

                this.clearSubmitValue();
            },
            clearSubmitValue: function () {
                this.showValueInput = false;
                this.searchValue = "";
            },
            removeValue: function (value) {
                var values = this.attribute['values'];
                values.$remove(value);
            },
            removeAttr: function () {
                this.$dispatch('attrDeleted', this.index);
            }
        }
    });
</script>
