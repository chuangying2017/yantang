<?php namespace App\Repositories\Order;


use App\Services\Order\OrderProtocol;

class CollectClientOrderRepository extends ClientOrderRepository {

    protected $detail_relations = ['skus', 'address', 'billings', 'deliver', 'memo'];
    protected $lists_relations = ['skus'];

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_COLLECT;
    }

}
