<?php namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\NoGenerator;
use App\Repositories\Order\Refund\RefundOrderRepositoryContract;
use App\Services\Order\OrderProtocol;
use DB;

class RefundClientOrderRepository extends ClientOrderRepository implements RefundOrderRepositoryContract {

    protected $detail_relations = ['skus', 'billings', 'deliver', 'memo'];
    protected $lists_relations = ['skus'];

    protected function setOrderType()
    {
        $this->type = OrderProtocol::ORDER_TYPE_OF_REFUND;
    }

    public function createOrder($temp_order)
    {
        $refer_order = $temp_order['order'];
        $refund_amount = $temp_order['refund_amount'];
        $discount_amount = $temp_order['discount_amount'];

        DB::beginTransaction();
        $order = Order::create([
            'user_id' => $refer_order['user_id'],
            'order_no' => NoGenerator::generateRefundNo(),
            'total_amount' => $refund_amount,
            'products_amount' => $refund_amount,
            'discount_amount' => $discount_amount,
            'express_fee' => 0,
            'pay_amount' => $temp_order['pay_amount'],
            'order_type' => $this->type,
            'pay_status' => OrderProtocol::PAID_STATUS_OF_UNPAID,
            'status' => OrderProtocol::REFUND_STATUS_OF_APPLY,
            'pay_type' => OrderProtocol::PAY_TYPE_OF_ONLINE,
            'deliver_type' => OrderProtocol::getDeliverType($this->type),
        ]);

        $order->refer()->attach([
            'order_id' => $refer_order['id'],
        ]);
        $order->refer()->update(['refund_status' => $order['status']]);

        $order->skus = $this->orderSkuRepo->createOrderSkus($order, $temp_order['skus']);
        $order->memo = $this->createOrderMemo($order['id'], array_get($temp_order, 'memo', ''));

        DB::commit();

        return $order;

    }

    public function updateRefundAsApprove($order_no)
    {
        return $this->updateRefundOrderStatus($order_no, OrderProtocol::REFUND_STATUS_OF_APPROVE);
    }

    public function updateRefundAsReject($order_no)
    {
        return $this->updateRefundOrderStatus($order_no, OrderProtocol::REFUND_STATUS_OF_REJECT);
    }

    public function updateRefundAsRefunding($order_no)
    {
        return $this->updateRefundOrderStatus($order_no, OrderProtocol::REFUND_STATUS_OF_REFUNDING);
    }

    public function updateRefundAsDone($order_no)
    {
        return $this->updateRefundOrderStatus($order_no, OrderProtocol::REFUND_STATUS_OF_DONE);
    }

    public function updateRefundAsFail($order_no)
    {
        return $this->updateRefundOrderStatus($order_no, OrderProtocol::REFUND_STATUS_OF_FAIL);
    }

    public function updateOrderStatusAsDeliver($order_no)
    {
        return $this->updateRefundOrderStatus($order_no, OrderProtocol::REFUND_STATUS_OF_SHIPPING);
    }

    public function updateOrderStatusAsDeliverDone($order_no)
    {
        return $this->updateRefundOrderStatus($order_no, OrderProtocol::REFUND_STATUS_OF_SHIPPED);
    }

    protected function updateRefundOrderStatus($order_no, $status)
    {
        $order = $this->getOrder($order_no);
        $order['status'] = $status;

        $order->save();

        $order->refer()->update(['refund_status' => $status]);

        return $order;
    }
}
