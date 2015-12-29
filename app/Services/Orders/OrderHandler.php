<?php namespace App\Services\Orders;

use App\Services\Orders\Payments\BillingManager;
use App\Services\Orders\Payments\BillingRepository;
use Exception;

class OrderHandler {

    public static function orderIsPaid($order_id)
    {
        $order = OrderRepository::queryOrderById($order_id);

        #check main billing
        if ( ! BillingManager::checkMainBillingIsPaid($order_id)) {

            throw new \Exception('订单未支付');
        }

        if ($order['status'] == OrderProtocol::STATUS_OF_UNPAID) {
            OrderRepository::updateStatus($order_id, OrderProtocol::STATUS_OF_PAID);
        }

    }

    private static function checkOrderSplit($order)
    {
        if ($order->children()->count()) {
            throw new \Exception('订单已拆分');
        }
    }

    public static function splitOrderByMerchant($order_id)
    {
        $order = OrderRepository::queryOrderById($order_id);

        self::checkOrderSplit($order);

        $order->load('products');

        $order_products = $order->products;

        if ( ! count($order_products)) {
            throw new \Exception('订单商品详情错误');
        }

        $merchant_orders = [];
        foreach ($order_products as $order_product) {
            $merchant_orders[ $order_product['merchant_id'] ]['order_product_id'][] = $order_product['id'];
            $merchant_orders[ $order_product['merchant_id'] ]['pay_amount'] = array_get($merchant_orders[ $order_product['merchant_id'] ], 'pay_amount', 0) + $order_product['pay_amount'] * $order_product['quantity'];
            $merchant_orders[ $order_product['merchant_id'] ]['discount_fee'] = array_get($merchant_orders[ $order_product['merchant_id'] ], 'discount_fee', 0) + $order_product['discount_amount'] * $order_product['quantity'];
            $merchant_orders[ $order_product['merchant_id'] ]['total_amount'] = array_get($merchant_orders[ $order_product['merchant_id'] ], 'total_amount', 0) + $order_product['price'] * $order_product['quantity'];
        }

        foreach ($merchant_orders as $merchant_id => $merchant_order_info) {
            $child_order_info = [
                'order_id'     => $order['id'],
                'merchant_id'  => $merchant_id,
                'total_amount' => $merchant_order_info['total_amount'],
                'pay_amount'   => $merchant_order_info['pay_amount'],
                'discount_fee' => $merchant_order_info['discount_fee'],
                'status'       => $order['status'],
            ];

            OrderRepository::createChildOrder($child_order_info, $merchant_order_info['order_product_id']);
        }
    }


}
