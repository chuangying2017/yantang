<?php namespace App\Http\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract {


    public function transform(Product $product)
    {
        $detail = [];
        if (isset($product->show_detail)) {
            $this->setDefaultIncludes(['img', 'skus']);
            $detail = $this->transformDetailData($product);
        }

        $base_info = [
            'id'          => (int)$product->id,
            'brand_id'    => $product->brand_id,
            'category_id' => (int)$product->category_id,
            'merchant_id' => (int)$product->merchant_id,
            'cover_image' => $product->cover_image,
            'product_no'  => $product->product_no,
            'title'       => $product->title,
            'open_status' => $product->open_status,
            'open_time'   => $product->open_time,
            'price'       => display_price($product->price),
            'sales'       => (int)$product->data->sales,
            'favs'        => (int)$product->data->favs,
            'stocks'      => (int)$product->data->stock,
            'express_fee' => (int)$product->data->express_fee,
            'faved'       => (boolean)$product->faved
        ];

        return array_merge($base_info, $detail);
    }

    public function includeImg(Product $product)
    {
        $images = $product->images;

        return $this->collection($images, new ImageTransformer());
    }

    public function includeSkus(Product $product)
    {
        $skus = $product->skuViews;

        return $this->collection($skus, new ProductSkuTransformer());
    }

    protected function transformDetailData(Product $product)
    {

        return array_merge($this->transformDetail($product), $this->transformMeta($product));
    }

    protected function transformDetail(Product $product)
    {
        return [
            'sub_title'    => $product->sub_title,
            'digest'       => $product->digest,
            'origin_price' => display_price($product->origin_pric),
        ];
    }

    protected function transformMeta(Product $product)
    {
        return isset($product->meta) ?
            [
                'attributes' => json_decode($product->meta->attributes, true),
                'detail'     => $product->meta->detail
            ] :
            [
                'attributes' => null,
                'detail'     => null
            ];
    }


}
