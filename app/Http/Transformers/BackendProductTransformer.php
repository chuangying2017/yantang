<?php namespace App\Http\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class BackendProductTransformer extends TransformerAbstract {


    public function transform(Product $product)
    {
        $detail = [];
        if (isset($product->show_detail)) {
            $this->setDefaultIncludes(['skus', 'images']);
            $detail = $this->transformDetailData($product);
        }

        $base_info = [
            'id'          => (int)$product->id,
            'product_no'  => $product->product_no,
            'brand_id'    => $product->brand_id,
            'category_id' => (int)$product->category_id,
            'merchant_id' => (int)$product->merchant_id,
            'title'       => $product->title,
            'price'       => (int)display_price($product->price),
            'cover_image' => $product->cover_image,
            'limit'       => $product->limit,
            'stock'       => $product->stock,
            'open_status' => $product->open_status,
            'open_time'   => $product->open_time,
            'express_fee' => (int)$product->data->express_fee,
            'created_at'  => $product->created_at->toDateTimeString(),
            'updated_at'  => $product->updated_at->toDateTimeString(),
        ];

        return array_merge($base_info, $detail);
    }

    public function includeImages(Product $product)
    {
        $images = $product->images;

        return $this->collection($images, new ImageTransformer());
    }


    public function includeSkus(Product $product)
    {
        $product->load('skuViews');
        $skus = $product->skuViews;

        return $this->collection($skus, new ProductSkuTransformer());
    }

    protected function transformDetailData(Product $product)
    {

        return array_merge(
            $this->transformDetail($product),
            $this->transformMeta($product),
            $this->transformImagesId($product),
            $this->transformGroups($product)
        );
    }

    protected function transformDetail(Product $product)
    {


        return [
            'sub_title'    => $product->sub_title,
            'digest'       => $product->digest,
            'origin_price' => (int)display_price($product->origin_price),
        ];
    }

    protected function transformImagesId(Product $product)
    {
        $images = $product->images;

        $image_ids = [];
        if (count($images)) {
            foreach ($images as $image) {
                $image_ids[] = $image['id'];
            }
        }

        return compact('image_ids');
    }

    protected function transformGroups(Product $product)
    {
        $groups = $product->groups;

        $group_ids = [];
        if (count($groups)) {
            foreach ($groups as $group) {
                $group_ids[] = $group['id'];
            }
        }

        return ['group_ids' => $group_ids];
    }

    protected function transformMeta(Product $product)
    {
        return isset($product->meta) ?
            [
                'attributes' => json_decode($product->meta->attributes, true),
                'detail'     => $product->meta->detail,
            ] :
            [
                'attributes' => null,
                'detail'     => null
            ];
    }


}
