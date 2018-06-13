<?php namespace App\Api\V1\Transformers\Admin\Product;


use App\Api\V1\Transformers\Mall\ProductInfoTransformer;
use App\Api\V1\Transformers\Mall\ProductMetaTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Product\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['skus', 'brand', 'info', 'meta', 'cats', 'groups'];


    public function transform(Product $product)
    {
        $this->setInclude($product);

        $data = [
            'id' => $product['id'],
            'brand' => ['id' => $product['brand_id']],
            'product_no' => $product['product_no'],
            'title' => $product['title'],
            'sub_title' => $product['sub_title'],
            'digest' => $product['digest'],
            'cover_image' => $product['cover_image'],
            'price' => display_price($product['price']),
            'status' => $product['status'],
            'type' => $product['type'],
            'open_time' => $product['open_time'],
            'end_time' => $product['end_time'],
            'priority' => $product['priority'],
            'created_at' => $product['created_at'],
            'updated_at' => $product['updated_at'],
            'clientWeChatUrl'=>$product['url'],
            'dismode' => $product->skus[0]['dismode'],//配送方式
        ];

        if ($product->relationLoaded('images')) {
            $data['image_ids'] = $product->images->pluck('media_id')->all();
            $data['images'] = array_map(function ($media_id) {
                return [
                    'media_id' => $media_id,
                    'url' => config('filesystems.disks.qiniu.domains.custom') . $media_id
                ];
            }, $data['image_ids']);
        }
        return $data;
    }

    public function includeSkus(Product $product)
    {
        return $this->item($product->skus->first(), new ProductSkuTransformer(), true);
    }

    public function includeBrand(Product $product)
    {
        return $this->item($product->brand, new ProductSkuTransformer(), true);
    }

    public function includeInfo(Product $product)
    {
        return $this->item($product->info, new ProductInfoTransformer(), true);
    }

    public function includeMeta(Product $product)
    {
        return $this->item($product->meta, new ProductMetaTransformer(), true);
    }

    public function includeCats(Product $product)
    {
        return $this->item($product->cats, new CategoryTransformer(), true);
    }

    public function includeGroups(Product $product)
    {
        return $this->collection($product->groups, new GroupTransformer(), true);
    }

}
