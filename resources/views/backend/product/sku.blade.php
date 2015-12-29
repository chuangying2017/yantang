<script type="x-template" id="sku-tpl">
    <table class="table table-bordered">
        <thead>
        <th v-for="attr in attributes">[! attr.name !]</th>
        <th>价格（元）</th>
        <th>库存</th>
        </thead>
        <tbody>
        <tr v-for="sku in skus">
            <td v-for="attr in sku.attributes">[! attr.attribute_value_name !]</td>
            <td><input type="text" class="form-control" v-model="skus[$index]['price']"/></td>
            <td><input type="text" class="form-control" v-model="skus[$index]['stock']"/></td>
        </tr>

        </tbody>
    </table>
</script>
<script>
    var getCombinations = function (arr, n) {
        if (n == 1) {
            var ret = [];
            for (var i = 0; i < arr.length; i++) {
                for (var j = 0; j < arr[i].length; j++) {
                    ret.push([arr[i][j]]);
                }
            }
            return ret;
        }
        else {
            var ret = [];
            for (var i = 0; i < arr.length; i++) {
                var elem = arr.shift();
                for (var j = 0; j < elem.length; j++) {
                    var childperm = getCombinations(arr.slice(), n - 1);
                    for (var k = 0; k < childperm.length; k++) {
                        ret.push([elem[j]].concat(childperm[k]));
                    }
                }
            }
            return ret;
        }
    }
    Vue.component('sku', {
        template: '#sku-tpl',
        data: function () {
            return {}
        },
        created: function () {

        },
        methods: {
            render: function () {
                var arr = [];
                var skusArr = [];
                for (var i = 0; i < this.attributes.length; i++) {
                    arr.push(this.attributes[i]['values'])
                }
                var possibility = getCombinations(arr, arr.length);

                for (var j = 0; j < possibility.length; j++) {
                    skusArr[j] = {
                        name: "",
                        cover_image: "",
                        stock: 0,
                        price: 0,
                        attributes: [],
                        attribute_value_ids: []
                    }
                    for (var k = 0; k < possibility[j].length; k++) {
                        skusArr[j]['attributes'].push({
                            attribute_value_name: possibility[j][k]['name']
                        });
                        skusArr[j]['attribute_value_ids'].push(parseInt(possibility[j][k]['id']));
                    }
                }

                this.skus = _.clone(skusArr);
            }
        },
        watch: {
            'attributes': function () {
                this.render();
            }
        },
        events: {
            'value-change': function () {
                this.render();
            }
        },
        props: ['attributes', 'skus', 'length']
    })
</script>
