<?php namespace App\Api\V1\Transformers\Subscribe;

use App\Models\Product\Product;
use App\Repositories\CategoryRepository;
use League\Fractal\TransformerAbstract;

class ProductSkuTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {
        $sku = $product->skus->first();
        $cat = $product->cats->first();
        $info = $product->info;
        $image = $product->images->pluck('media_id')->all();
        return [
            'id' => $sku['id'],
            'name' => $sku['name'],
            'cover_image' => $sku['cover_image'],
            'digest' => $product['digest'],
            'cat' => [
                'id' => $cat['id'],
                'name' => $cat['name']
            ],
            'price' => display_price($sku['subscribe_price']),
            'attr' => json_decode($sku['attr'], true),
            'unit' => $sku['unit'],
            'detail' => $info['detail'],
            'images' => array_map(function ($media_id){ return config('filesystems.disks.qiniu.domains.custom').$media_id;},$image),
            'dismode' => $sku['dismode'],
        ];
    }
}
