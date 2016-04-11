<?php namespace App\Services\Marketing\Exceptions;
use Exception;

class CouponValidationException extends Exception{

    public function __construct($item)
    {
        parent::__construct($item . '优惠创建请求内容格式错误');
    }
}
