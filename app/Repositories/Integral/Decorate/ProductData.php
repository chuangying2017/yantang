<?php
namespace App\Repositories\Integral\Decorate;

use App\Models\Integral\Product;
use App\Repositories\Integral\Editor\EditorAbstract;
use App\Services\Integral\Product\ProductProtocol;

class ProductData extends EditorAbstract
{

    public function handle(array $data, Product $product)
    {
      $product->fill($this->fillArray($data))->save();

      return $this->next($data,$product);
    }

    protected function fillArray(array $array)
    {
        $arr = array_only($array,[
            'category_id',//分类id
            'title',//产品标题
            'price',//产品原来价格
            'sort_type',//排序类型
            'integral',//积分
            'cover_image',//首页小图
            'digest',//商品描述
            'advertising',//广告语
            'priority',//优先权限
            'open_time',//产品兑换时间
            'end_time',//产品停止兑换时间
            'recommend',//是否为推荐商品
            'detail',//商品详情
            'type',//not_limit 不限制 on_limit限制时间
            'product_no',//商品编号
        ]);

        $arr['product_no'] = array_get($arr,'product_no',(string)uniqid('fn_'));

        $arr['status']  = array_get($arr,'status',ProductProtocol::INTEGRAL_PRODUCT_STATUS_DOWN);

        $arr['type']    = array_get($arr,'type',ProductProtocol::INTEGRAL_PRODUCT_TYPE_NOT_LIMIT);

        return $arr;
    }
}