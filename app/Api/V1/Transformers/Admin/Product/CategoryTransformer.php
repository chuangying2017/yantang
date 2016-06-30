<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract {

    public function transform(Collection $category)
    {
        return $category->toArray();
    }

}
