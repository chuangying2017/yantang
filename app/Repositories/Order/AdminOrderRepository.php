<?php namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\NoGenerator;
use App\Services\Order\OrderProtocol;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminOrderRepository implements AdminOrderRepositoryContract {

    public function updateOrderStatus($order_no, $status)
    {
        // TODO: Implement updateOrderStatus() method.
    }

    public function getOrder($order_no)
    {
        if ($order_no instanceof Order) {
            $order = $order_no;
        } else if (NoGenerator::isOrderNo($order_no)) {
            $order = Order::where('order_no', $order_no)->first();
        } else {
            $order = Order::find($order_no);
        }

        if (!$order) {
            throw new ModelNotFoundException();
        }

        $order->load('skus', 'address', 'billings', 'order_promotion');

        return $order;
    }

    public function getAllOrders($status, $keyword, $order_by = 'created_at', $sort = 'desc')
    {
        // TODO: Implement getAllOrders() method.
    }

    public function getPaginatedOrders($status, $keyword, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE)
    {
        // TODO: Implement getPaginatedOrders() method.
    }
}
