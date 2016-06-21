<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Models\Product\CategoryAbstract;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract {

    public function transform(CategoryAbstract $category)
    {
        return $category->toArray();
    }

}
