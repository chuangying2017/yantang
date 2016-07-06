<?php namespace App\Repositories\Order;

use App\Services\Order\OrderProtocol;

class PreorderOrderRepository extends ClientOrderRepository {

    protected $detail_relations = ['skus', 'skus.counter', 'address', 'billings'];
    protected $lists_relations = ['skus', 'skus.counter'];

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE;
    }


}
