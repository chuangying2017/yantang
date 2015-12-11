<?php namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderProduct;
use DB;


class OrderRepository {

    const ORDER_NO_LENGTH = 28;

    public static function generateOrder($order_info)
    {
        return DB::transaction(function () use ($order_info) {

            $order_main_info = self::encodeOrderData($order_info);
            $order_main = Order::create($order_main_info);
            $order_id = $order_main['id'];

            self::storeOrderProducts($order_info['products'], $order_id);
            self::storeAddress($order_info['address'], $order_id);

            return $order_main;

        });
    }

    protected static function encodeOrderData($order_info)
    {
        $order_no = self::generateOrderNo();
        $total_amount = $order_info['total_amount'];
        $user_id = $order_info['user_id'];
        $title = $order_info['title'];
        $post_fee = array_get($order_info, 'post_fee', 0);
        $discount_amount = array_get($order_info, 'discount_fee', 0);
        $pay_amount = bcsub(bcadd($total_amount, $post_fee), $discount_amount);
        $memo = array_get($order_info, 'memo', '');
        $status = OrderProtocol::STATUS_OF_UNPAID;
        $pay_type = $order_info['pay_type'];

        $order_main_info = compact('title', 'order_no', 'total_amount', 'post_fee', 'discount_amount', 'pay_amount', 'memo', 'status', 'pay_type', 'user_id');

        return $order_main_info;
    }

    protected static function storeOrderProducts($order_products, $order_id)
    {
        $order_products_info = [];
        foreach ($order_products as $key => $order_product) {
            $order_products_info[ $key ] = [
                'order_id'        => $order_id,
                'merchant_id'     => $order_product['merchant_id'],
                'product_sku_id'  => $order_product['product_sku_id'],
                'quantity'        => $order_product['quantity'],
                'price'           => $order_product['price'],
                'discount_amount' => array_get($order_product, 'discount_amount', 0),
                'pay_amount'      => bcsub($order_product['price'], array_get($order_product, 'discount_amount', 0))
            ];
        }
        DB::table('order_products')->insert($order_products_info);
    }


    protected static function storeAddress($address, $order_id)
    {
        $address_info = [
            'order_id' => $order_id,
            'name'     => array_get($address, 'name'),
            'mobile'   => array_get($address, 'mobile'),
            'province' => array_get($address, 'province'),
            'city'     => array_get($address, 'city'),
            'district' => array_get($address, 'district'),
            'detail'   => array_get($address, 'detail'),
            'tel'      => array_get($address, 'tel', ''),
            'zip'      => array_get($address, 'zip', ''),
        ];

        DB::table('order_address')->insert($address_info);
    }


    public static function queryOrderByOrderNo($order_no)
    {
        if ($order_no instanceof Order) {
            $order = $order_no;
        }

        return Order::where('order_no', $order_no)->firstOrFail();
    }

    public static function queryOrderById($order_id)
    {
        if ($order_id instanceof Order) {
            $order = $order_id;
        }

        return Order::firstOrFail($order_id);
    }

    public static function queryFullOrder($order)
    {
        $relation = ['skus', 'address', 'billings', 'express'];
        if ($order instanceof Order) {
            return $order->load($relation);
        }

        return Order::with($relation)->where('id', $order)->first();
    }


    public static function lists($user_id, $status = null, $paginate = null, $sort_by = null, $sort_type = 'desc', $relation = 'skus')
    {
        if ( ! is_null($relation)) {
            $query = Order::with($relation)->where('user_id', $user_id);
        } else {
            $query = Order::where('user_id', $user_id);
        }

        if ( ! is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();

    }

    protected static function generateOrderNo()
    {
        $order_no = mt_rand(1000000, 9999999) . date('YmdHis') . mt_rand(1000000, 9999999);

        while (Order::where('order_no', $order_no)->count()) {
            $order_no = mt_rand(1000000, 9999999) . date('YmdHis') . mt_rand(1000000, 9999999);
        }

        return $order_no;
    }

}
