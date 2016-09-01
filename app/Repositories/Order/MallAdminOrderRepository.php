<?php namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\NoGenerator;
use App\Services\Order\OrderProtocol;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MallAdminOrderRepository extends AdminOrderRepositoryAbstract {
    
    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_MALL_MAIN;
    }
    
}
