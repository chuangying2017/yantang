<?php namespace App\Repositories\Order;


use App\Services\Order\OrderProtocol;

class MallEloquentClientOrderRepository extends EloquentClientOrderRepository {


    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN;
    }

}
