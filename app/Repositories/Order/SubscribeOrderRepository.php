<?php namespace App\Repositories\Order;

use App\Services\Order\OrderProtocol;

class SubscribeOrderRepository extends AdminOrderRepositoryAbstract {

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE;//setting orders type
    }

}
