<?php namespace App\Repositories\Order\Deliver;

use App\Models\Order\OrderDeliver;
use App\Services\Order\OrderProtocol;

class OrderDeliverRepository {

    public function createOrderDeliver($order_id, $company, $post_no)
    {
        $order_deliver = OrderDeliver::findOrNew($order_id);
        $order_deliver->order_id = $order_id;
        $order_deliver->company_name = $company;
        $order_deliver->post_no = $post_no;
        $order_deliver->status = OrderProtocol::STATUS_OF_SHIPPING;
        $order_deliver->save();
        return $order_deliver;
    }

    public function updateOrderDeliverAsDone($order_id)
    {
        return $this->updateOrderDeliverStatus($order_id, OrderProtocol::STATUS_OF_SHIPPED);
    }

    protected function updateOrderDeliverStatus($order_id, $status)
    {
        $order_deliver = OrderDeliver::findOrFail($order_id);
        $order_deliver->status = $status;
        $order_deliver->save();
        return $order_deliver;
    }



}
