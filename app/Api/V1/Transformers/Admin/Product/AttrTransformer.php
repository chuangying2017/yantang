<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Models\Product\Attribute;
use League\Fractal\TransformerAbstract;

class AttrTransformer extends TransformerAbstract {

    public function transform(Attribute $attr)
    {
        return $attr->toArray();
    }

}
