<?php namespace App\Services\Pay\Exception;
class WrongChargeBillingException extends \Exception{

    public function __construct()
    {
        parent::__construct('错误的充值订单');
    }

}
