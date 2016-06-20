<?php namespace App\Http\Transformers;
use App\Models\Product\CategoryAbstract;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract{

    public function transform(CategoryAbstract $category)
    {
        return [
            'id' => (int)$category,
            'name' => $category
        ];
    }
}
