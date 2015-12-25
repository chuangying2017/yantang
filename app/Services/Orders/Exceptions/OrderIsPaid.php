<?php namespace App\Services\Orders\Exceptions;
class OrderIsPaid extends \Exception {

    public function __construct()
    {
        parent::__construct('订单无需重复支付');
    }

}
