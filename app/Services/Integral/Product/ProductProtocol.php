<?php
namespace App\Services\Integral\Product;

class ProductProtocol
{
    const INTEGRAL_PRODUCT_STATUS_UP                    = 'up';             //上架
    const INTEGRAL_PRODUCT_STATUS_DOWN                  = 'down';           //下架
    const INTEGRAL_PRODUCT_STATUS_REMAINDER             = 'remainder';      //售完
    const INTEGRAL_PRODUCT_STATUS_DELETE                = 'delete';         //删除

    const INTEGRAL_PRODUCT_TYPE_ON_LIMIT                = 'on_limit';       //限制
    const INTEGRAL_PRODUCT_TYPE_NOT_LIMIT               = 'not_limit';      //不限制

    //积分添加插入规则
    const INTEGRAL_PRODUCT_INSERTION_RULE_ARRAY         = [
        'category_id',//分类id
        'title',//产品标题
        'price',//产品原来价格
        'sort_type',//排序类型
        'integral',//积分
        'cover_image',//首页小图
        'digest',//商品描述
        'advertising',//广告语
        'status',//产品状态 上架 下架 售完
        'priority',//优先权限
        'open_time',//产品兑换时间
        'end_time',//产品停止兑换时间
        'recommend',//是否为推荐商品
        'detail',//商品详情
        'unit',//产品单位
        'quantity',//发布数量/库存
        'sales',//可兑换量
        'postage',//邮费
        'name',
        'image_ides',//多图片
        'type',
    ];
}