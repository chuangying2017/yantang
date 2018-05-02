<?php namespace App\Services\Pay\Exception;
class MultiChargeException extends \Exception {

    public function __construct()
    {
        parent::__construct('充值订单已处理');
    }
}
