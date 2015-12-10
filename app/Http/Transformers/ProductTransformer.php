<?php namespace App\Http\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract {

    protected $defaultIncludes = [];

    public function transform(Product $product)
    {
        $detail = [];
        if (isset($product->show_detail)) {
            $detail = $this->transformDetailData($product);
        }

        $base_info = [
            'id'          => (int)$product->id,
            'cover_image' => $product->cover_image,
            'product_no'  => $product->product_no,
            'title'       => $product->title,
            'price'       => (int)$product->price,
            'sales'       => (int)$product->data->sales,
            'favs'        => (int)$product->data->favs,
            'stocks'      => (int)$product->data->stock,
            'express_fee' => (int)$product->data->express_fee,
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
        $this->defaultIncludes = array_merge($this->defaultIncludes, ['img', 'skus']);

        return array_merge($this->transformDetail($product), $this->transformMeta($product));
    }

    protected function transformDetail(Product $product)
    {
        return [
            'sub_title'    => $product->sub_title,
            'digest'       => $product->digest,
            'origin_price' => (int)$product->origin_price,
        ];
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
