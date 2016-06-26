<?php namespace App\Repositories\Product\Editor;

use App\Models\Product\Product;
use App\Repositories\Product\Attribute\AttributeValueRepositoryContract;

class SetAttributes extends EditorAbstract {

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
        if (!isset($product_data['attr'])) {
            $product_data['attr'] = '';
        }

        $product_data['attr'] = $this->encodeAttr($product_data['attr']);

        foreach ($product_data['skus'] as $key => $sku) {
            $product_data['skus'][$key]['attr'] = $this->encodeAttr($this->getSkuAttributes($sku));
        }

        return $this->next($product_data, $product);
    }

    protected function getSkuAttributes($sku)
    {
        if (empty($sku['attr_value_ids'])) {
            return [];
        }

        $attributes = [];
        $attribute_values = $this->attr_value_repository->getValues($sku['attr_value_ids']);
        foreach ($attribute_values as $attribute_value) {
            $attributes[] = [
                'attr_value_id' => $attribute_value['id'],
                'attr_value_name' => $attribute_value['name'],
                'attr_id' => $attribute_value['attr']['id'],
                'attr_name' => $attribute_value['attr']['name'],
            ];
        }

        return $attributes;
    }

    protected function encodeAttr($attr)
    {
        return empty($attr) ? '' : json_encode($attr);
    }


}
