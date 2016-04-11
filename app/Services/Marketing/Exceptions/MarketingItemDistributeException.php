<?php namespace App\Services\Marketing\Exceptions;
class MarketingItemDistributeException extends \Exception{

    public function __construct($reason = null)
    {
        $message = is_null($reason) ? '获取优惠失败' : $reason . ', 获取优惠失败';
        parent::__construct($message);
    }
}
