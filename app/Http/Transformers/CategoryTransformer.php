<?php namespace App\Http\Transformers;
use App\Models\Product\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract{

    public function transform(Category $category)
    {
        return [
            'id' => (int)$category,
            'name' => $category
        ];
    }
}
