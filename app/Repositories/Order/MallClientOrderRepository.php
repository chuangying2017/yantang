<?php namespace App\Repositories\Order;


use App\Services\Order\OrderProtocol;

class MallClientOrderRepository extends ClientOrderRepositoryAbstract {

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN;
    }

}
