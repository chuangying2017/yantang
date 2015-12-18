<script type="x-template" id="sku-tpl">
    <table class="table table-bordered">
        <thead>
        <th v-for="attr in attributes">[! attr.name !]</th>
        <th>价格（元）</th>
        <th>库存</th>
        </thead>
        <tbody>
        <tr v-for="value in attributes[0]['values']">
            <td>[! value.name !]</td>
            <td v-for="val2 in attributes[1]['values']">[! val2.name !]</td>
            <td><input type="text" class="form-control"/></td>
            <td><input type="text" class="form-control"/></td>
        </tr>

        </tbody>
    </table>
</script>
<script>

    var getAllPossible = function (arr) {
        if (arr.length === 0) {
            return [];
        }
        else if (arr.length === 1) {
            return arr[0];
        }
        else {
            var result = [];
            var allCasesOfRest = allPossibleCases(arr.slice(1));  // recur with the rest of array
            for (var c in allCasesOfRest) {
                for (var i = 0; i < arr[0].length; i++) {
                    result.push(arr[0][i] + allCasesOfRest[c]);
                }
            }
            return result;
        }
    }

    Vue.component('sku', {
        template: '#sku-tpl',
        data: function () {
            return {}
        },
        computed: {
            list: function () {
                
            }
        },
        created: function () {
        },
        props: ['attributes', 'skus']
    })
</script>
