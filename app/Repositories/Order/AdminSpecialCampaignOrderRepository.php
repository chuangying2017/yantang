<?php namespace App\Repositories\Order;

use App\Services\Order\OrderProtocol;

class AdminSpecialCampaignOrderRepository extends AdminOrderRepositoryAbstract {

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_CAMPAIGN;
    }
}
