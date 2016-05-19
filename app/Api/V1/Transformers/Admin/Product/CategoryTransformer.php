<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Models\Product\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract {

    public function transform(Category $category)
    {
        return $category->toArray();
    }

}
