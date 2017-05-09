<?php namespace App\Api\V1\Transformers;

use App\Models\Banner;
use League\Fractal\TransformerAbstract;

class BannerTransformer extends TransformerAbstract {
    public function transform(Banner $banner)
    {
        $data = $banner->toArray();
        return $data;
    }
}
