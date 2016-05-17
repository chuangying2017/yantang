<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\Attribute\AttributeValueRepositoryContract;

class CheckAttributes extends ProductEditor {

    private $attr_value_repository;

    protected $product_attr = [];

    /**
     * CheckAttributes constructor.
     * @param AttributeValueRepositoryContract $attr_value_repository
     */
    public function __construct(AttributeValueRepositoryContract $attr_value_repository)
    {
        $this->attr_value_repository = $attr_value_repository;
    }

    public function handle(array $product_data, Product $product)
    {
        foreach ($product_data['sku'] as $key => $sku) {
            $sku_attr = $this->getSkuAttributes($sku);
            $product_data['sku'][$key]['attr'] = json_encode($sku_attr);
        }

        $product_data['attr'] = json_encode($product_data['attr']);

        return $this->next($product_data, $product);
    }

    protected function getSkuAttributes($sku)
    {
        $attribute_values = $this->attr_value_repository->getValues($sku['attr_value_ids']);
        foreach ($attribute_values as $attribute_value) {
            $attributes[] = [
                'attr_value_id' => $attribute_value['id'],
                'attr_value_name' => $attribute_value['name'],
                'attr_id' => $attribute_value['attr']['id'],
                'attr_name' => $attribute_value['attr']['name'],
            ];
        }

        return $attribute_values;
    }


}
