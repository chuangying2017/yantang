<?php

namespace App\Api\V1\Transformers\Integral\Admin;

use App\Models\Integral\Product;
use League\Fractal\TransformerAbstract;

class IntegralProductTransformer extends TransformerAbstract {

    protected $availableIncludes = ['sku'];

    public function transform(Product $product)
    {
            return [
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
               // 'open_time',//产品兑换时间
               // 'end_time',//产品停止兑换时间
                'recommend'=>$product['recommend'],//是否为推荐商品
                'detail'=>$product['detail'],//商品详情
                'type'=>$product['type'],//not_limit 不限制 on_limit限制时间
            ];
    }

    public function IncludeSku(Product $product)
    {
        return $this->item($product->product_sku, new IntegralProductSkuTransformer());
    }
}
