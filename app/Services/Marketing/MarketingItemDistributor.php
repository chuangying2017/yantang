<?php namespace App\Services\Marketing;

abstract class MarketingItemDistributor {


    //验证用户是否参加获取优惠
    protected abstract function auth($id, $user_id);

    //用户获取优惠
    public abstract function send($id, $user_id);

    protected abstract function sendSucceed($id, $user_id);

    //规则验证
    public function filer($user_id, $resource_id)
    {
        #todo filter the get rules
    }





}
