<script type="x-template" id="sku-tpl">
    <table class="table table-bordered">
        <thead>
        <th v-for="attr in attributes">[! attr.name !]</th>
        <th>价格（元）</th>
        <th>库存</th>
        </thead>
        <tbody>
        <tr>
            <td><input type="text" class="form-control"/></td>
            <td><input type="text" class="form-control"/></td>
        </tr>

        </tbody>
    </table>
</script>
<script>
    var getCombinations = function (arr, n) {
        console.log('here')
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
            return {
                skus: []
            }
        },
        computed: {
            list: function () {

            }
        },
        watch: {
            attributes: function (newVal, oldVal) {
                var arr = [];
                for (var i = 0; i < newVal.length; i++) {
                    if (newVal[i]['values']) {
                        arr.push(newVal[i].values);
                    }
                }
                console.log(arr);
                if (arr.length > 0) {
                    var t = getCombinations(arr, arr.length);
                    console.log(t);
                }
            }
        },
        created: function () {
        },
        props: ['attributes', 'skus']
    })
</script>
