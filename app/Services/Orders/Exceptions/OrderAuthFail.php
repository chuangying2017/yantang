<?php namespace App\Services\Orders\Exceptions;

class OrderAuthFail extends \Exception{

    public function __construct()
    {
        parent::__construct('当前用户无权操作该订单');
    }
}
