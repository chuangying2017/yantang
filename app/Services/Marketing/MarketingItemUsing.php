<?php namespace App\Services\Marketing;

abstract class MarketingItemUsing {

    protected $resource_type;

    //用户是用优惠
    public abstract function used($id, $user_id);


    //查询优惠是否可用

    /**
     * @param array $resource
     * @param $user_id
     */
    public function filter(Array $resource, $order_detail)
    {
        #todo 过滤传入的优惠券信息是否可用于购买信息
        return [];
    }

    public function calculateDiscountFee($resource_id, $total_amount)
    {
        #todo 获取优惠详情，计算优惠额度
    }

}
