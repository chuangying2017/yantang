<?php namespace App\Repositories\Order;

use App\Models\Collect\CollectOrder;

class CollectOrderRepository implements ClientOrderRepositoryContract{
    protected $lists_relations = ['sku','address'];

    /**
     * @param $data
     */
    public function createOrder($data)
    {
        $order = CollectOrder::createOrder($data);

        return $order;
    }

    public function getPaginatedOrders($status = null, $order_by = 'created_at', $sort = 'desc', $per_page = CollectOrderProtocol::ORDER_PER_PAGE)
    {
        $query = CollectOrder::query();
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with($this->lists_relations)->orderBy($order_by, $sort)->paginate($per_page);
    }



    public function updateOrderStatus($order_id, $status){

    }

    public function updateOrderPayStatus($order_id, $status, $pay_channel = null){

    }

    public function updateOrderStatusAsPaid($order_id, $pay_channel){

    }

    public function updateOrderStatusAsDeliver($order_id){

    }

    public function updateOrderStatusAsDeliverDone($order_id){

    }

    public function updateOrderStatusAsDone($order_id){

    }

    public function updateOrderStatusAsCancel($order_id){

    }

    public function getOrder($order_no, $with_detail = false){

    }

    public function getAllOrders($status = null, $order_by = 'created_at', $sort = 'desc'){

    }

}
