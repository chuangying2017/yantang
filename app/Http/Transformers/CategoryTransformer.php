<?php namespace App\Http\Transformers;
use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract{

    #todo @troy
    public function transform(Category $category)
    {
        return [
            'id' => (int)$category,
            'name' => $category
        ];
    }
}
