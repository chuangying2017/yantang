<?php

namespace App\Api\V1\Transformers\Integral\Admin;

use App\Api\V1\Transformers\Integral\IntegralTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Integral\Product;
use League\Fractal\TransformerAbstract;

class IntegralProductTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['product_sku','integral_category'];

    public function transform(Product $product)
    {
        $this->setInclude($product);

            $data = [
                'id'=>$product['id'],
                'category_id'=>$product['category_id'],//分类id
                'title'=>$product['title'],//产品标题
                'price'=>$product['price'],//产品原来价格
                'sort_type'=>$product['sort_type'],//排序类型
                'integral'=>$product['integral'],//积分
                'cover_image'=>$product['cover_image'],//首页小图
                'digest'=>$product['digest'],//商品描述
                'advertising'=>$product['advertising'],//广告语
                'priority'=>$product['priority'],//优先权限
                'status'=>$product['status'],
               // 'open_time',//产品兑换时间
               // 'end_time',//产品停止兑换时间
                'recommend'=>$product['recommend'],//是否为推荐商品
                'detail'=>$product['detail'],//商品详情
                'type'=>$product['type'],//not_limit 不限制 on_limit限制时间
                'categoryName'=>isset($product->integral_category->first()->title)?$product->integral_category->first()->title:null,
            ];

            if($product->relationLoaded('images'))
            {
                $data['images'] = array_map(function($image_id){
                     return ['url'=>config('filesystems.disks.qiniu.domains.custom').$image_id,'media_id'=>$image_id];
                    },$product->images->pluck('media_id')->all());
            }

            if ($product->relationLoaded('specification'))
            {
                $data['specification'] = $product->specification->pluck('id');
            }

            return $data;
    }

    public function includeProductSku(Product $product)
    {
        return $this->item($product->product_sku, new IntegralProductSkuTransformer());
    }

    public function includeIntegralCategory(Product $product)
    {
        return $this->item($product->integral_category->first(), new IntegralTransformer());
    }
}
