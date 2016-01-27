<?php namespace App\Http\Transformers;

use App\Models\Channel;
use League\Fractal\TransformerAbstract;

class ChannelTransformer extends TransformerAbstract {

    public function transform(Channel $channel)
    {
        $this->setDefaultIncludes(['brands']);

        return [
            'id'          => (int)$channel->id,
            'name'        => $channel->name,
            'cover_image' => $channel->cover_image,
            'index'       => $channel->index,
            'active'      => $channel->active
        ];
    }

    public function includeBrands(Channel $channel)
    {
        $brands = $channel->brands;

        return $this->collection($brands, new BrandTransformer());
    }

}
