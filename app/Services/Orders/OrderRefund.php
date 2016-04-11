<?php namespace App\Services\Orders;

use App\Services\Orders\Event\OrderRefundApply;
use App\Services\Orders\Event\OrderRefundApprove;
use App\Services\Orders\Event\OrderRefunded;
use App\Services\Orders\Event\OrderRefunding;
use App\Services\Orders\Exceptions\OrderAuthFail;
use App\Services\Orders\Supports\PingxxPaymentRepository;
use App\Services\Orders\Supports\PingxxService;
use Carbon\Carbon;
use Exception;

class OrderRefund {

    public static function authOrder($user_id, $refund_order)
    {
        $order = OrderRepository::fetchRefundOrder($refund_order);
        if ($order->user_id == $user_id) {
            return $order;
        }

        throw new OrderAuthFail();
    }


    /**
     * 申请退货
     * @param $user_id
     * @param $order_no
     * @param $order_product_ids
     */
    public static function returns($user_id, $order_no, $refund_product_info, $memo)
    {
        //判断是否满足退货条件
        $order = self::checkOrder($user_id, $order_no);

        //使用优惠券的订单要整单退回
        if (self::hasDiscount($order)) {
            $order_products = OrderRepository::queryOrderProductByOrder($order['id']);
        } else {
            $order_products = self::checkProducts($order, $refund_product_info);
        }


        $order = self::generateRefundOrder($order, $order_products, $memo);

        return $order;
    }

    public static function deliver($user_id, $order_no, $company_name, $post_no)
    {
        $refund_order = OrderRepository::getRefundOrderByOrderNo($order_no);
        $refund_order = self::authOrder($user_id, $refund_order);

        return OrderRepository::updateRefundOrderDeliver($refund_order, $company_name, $post_no);
    }

    protected static function generateRefundOrder($order, $order_products, $memo)
    {

        //计算需要退还的金额
        $refund_amount = self::calRefundAmount($order, $order_products);
        //生成退货订单
        $refund_order_data = [
            'order_id'    => $order['id'],
            'user_id'     => $order['user_id'],
            'amount'      => $refund_amount,
            'status'      => OrderProtocol::STATUS_OF_RETURN_APPLY,
            'client_memo' => $memo,
        ];

        \DB::beginTransaction();

        $refund_order = OrderRepository::createRefundOrder($refund_order_data);

        //标记退货商品
        $refund_order_product_data = [];
        foreach ($order_products as $order_product) {
            $order_product_quantity = $order_product['quantity'];
            $order_product_amount = $order_product['pay_amount'];

            $refund_order_product_data[ $order_product['id'] ] = [
                'quantity' => $order_product_quantity,
                'amount'   => $order_product_amount
            ];
            OrderRepository::updateOrderProductRefund($order_product['id'], OrderProtocol::STATUS_OF_RETURN_APPLY, $order_product_quantity, $order_product_amount);
        }

        if (count($refund_order_product_data)) {
            $refund_order->skus()->attach($refund_order_product_data);
        }

        //标记退货商品子订单,主订单
        $order = OrderRepository::updateOrderRefund($order, OrderProtocol::STATUS_OF_RETURN_APPLY, $refund_amount);

        \DB::commit();

        //出发申请退货事件
        event(new OrderRefundApply($order));

        $order->refund_order = $refund_order;

        return $order;
    }

    /**
     * 计算需要退货商品的退款总额
     * @param $order
     * @param $order_products
     * @return int
     * @throws Exception
     */
    public static function calRefundAmount($order, $order_products)
    {
        $refund_total_amount = 0;

        foreach ($order_products as $order_product) {
            $refund_total_amount = $refund_total_amount + $order_product['pay_amount'];
        }

        if ($refund_total_amount > ($order['pay_amount'] - $order['refund_amount'])) {
            throw new \Exception('退款金额超过支付金额');
        }

        return $refund_total_amount;
    }

    /**
     * @param $order_product_info
     *
     * $order_product_info = [
     *      [
     *          'order_product_id': integer,
     *          'quantity': integer
     *      ],
     *      ...
     * ]
     *
     * @throws Exception
     *
     */
    protected static function checkProducts($order, $refund_product_info)
    {
        $order_product_ids = [];
        $order_product_info = [];
        foreach ($refund_product_info as $refund_product) {
            $order_product_ids[] = $refund_product['order_product_id'];
            $order_product_info[ $refund_product['order_product_id'] ] = $refund_product['quantity'];
        }
        array_unique($order_product_ids);


        $origin_order_products = OrderRepository::queryOrderProduct($order_product_ids);

        //检查商品是否存在
        if ( ! count($origin_order_products)) {
            throw new Exception('退货商品不存在');
        }

        $order_products = [];
        foreach ($origin_order_products as $key => $order_product) {
            //检查商品数量,是否同一个订单
            if ($order_product['order_id'] != $order['id']) {
                throw new \Exception('申请退货商品不是该订单');
            }
            $request_return_sku_quantity = $order_product_info[ $order_product['id'] ];
            if ((bcsub($order_product['quantity'], $order_product['return_quantity'])) < $request_return_sku_quantity) {
                throw new \Exception('退货商品数量不能超过购买数量');
            }
            $order_products[ $key ]['id'] = $order_product['id'];

            //计算单品退还数量与金额
            $order_products[ $key ]['pay_amount'] = bcmul($request_return_sku_quantity, bcdiv($origin_order_products[ $key ]['pay_amount'], $origin_order_products[ $key ]['quantity'], 0));
            $order_products[ $key ]['quantity'] = $request_return_sku_quantity;
        }

        return $order_products;
    }

    /**
     * 退款
     *
     * @param $user_id
     * @param $order_no
     * @param $amount
     */
    public static function refund($user_id, $order_no, $amount)
    {
        //计算需要退还的金额
        //判断是否能退还（时间,金额)
        //发起退款
    }


    /**
     * 检查原订单是否有使用优惠
     * @param $order
     * @return bool
     */
    protected static function hasDiscount($order)
    {
        return ($order['discount_amount'] > 0);
    }

    /**
     * 检查订单状态是否可以退货
     * @param $user_id
     * @param $order_no
     * @return mixed
     * @throws Exceptions\OrderAuthFail
     * @throws Exceptions\WrongStatus
     */
    protected static function checkOrder($user_id, $order_no)
    {
        $order = OrderService::authorder($user_id, $order_no);

        OrderProtocol::validStatus($order['status'], OrderProtocol::STATUS_OF_RETURN_APPLY);

        //支付超过七天
        if ($order['pay_at'] < Carbon::now()->subDay(7)) {
            throw new \Exception('超过退换时间');
        }

        return $order;
    }


    /**
     * ---------------------------------------------------------------------------------------------------------------------------------
     *
     * 管理员接口
     *
     * ---------------------------------------------------------------------------------------------------------------------------------
     */

    public static function lists($status = OrderProtocol::STATUS_OF_RETURN_APPLY, $paginate = 20)
    {
        $orders = OrderRepository::listsRefundOrder($status, $paginate);

        return $orders;
    }

    public static function show($id)
    {
        $order = OrderRepository::showRefundOrder($id);

        return $order;
    }

    public static function approve($refund_order_id, $memo = '')
    {
        $refund_order = OrderRepository::updateRefundOrderStatus($refund_order_id, OrderProtocol::STATUS_OF_RETURN_APPROVE, $memo);
        event(new OrderRefundApprove($refund_order));

        return $refund_order;
    }

    public static function reject($refund_order_id, $memo = '')
    {
        return OrderRepository::updateRefundOrderStatus($refund_order_id, OrderProtocol::STATUS_OF_RETURN_REJECT, $memo);
    }

    public static function refunding($refund_order_id)
    {
        $refund_order = OrderRepository::fetchRefundOrder($refund_order_id);
        //发起pingxx退款
        $desc = '退款订单ID: ' . $refund_order['id'] . ' 的退款';
        $result = PingxxService::refund($refund_order, $refund_order['amount'], $desc);
        //改变退款订单状态

        OrderRepository::updateRefundOrderStatus($refund_order, OrderProtocol::STATUS_OF_REFUNDING);

        event(new OrderRefunding($refund_order['order_id']));

        return $result;
    }

    public static function refunded($refund_order_id)
    {
        $refund_order = OrderRepository::fetchRefundOrder($refund_order_id);
        //修改退款订单状态
        OrderRepository::updateRefundOrderStatus($refund_order, OrderProtocol::STATUS_OF_REFUNDED);

        event(new OrderRefunded($refund_order['order_id']));
    }

}
