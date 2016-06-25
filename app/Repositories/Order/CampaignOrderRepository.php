<?php namespace App\Repositories\Order;

use App\Services\Order\OrderProtocol;

class CampaignOrderRepository extends ClientOrderRepository {

    protected $detail_relations = ['skus', 'billings', 'special', 'memo'];
    protected $lists_relations = ['skus', 'special'];

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_CAMPAIGN;
    }

    public function getAllOrders($status = null, $order_by = 'created_at', $sort = 'desc')
    {
        $query = Order::where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with('skus', 'special')->orderBy($order_by, $sort)->get();
    }

    public function getPaginatedOrders($status = null, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE)
    {
        $query = Order::where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with('skus', 'special')->orderBy($order_by, $sort)->paginate($per_page);
    }

}
