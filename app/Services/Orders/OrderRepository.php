<?php namespace App\Services\Orders;

use App\Models\ChildOrder;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Services\Orders\Event\OrderCancel;
use App\Services\Orders\Helpers\ExpressHelper;
use DB;


class OrderRepository {

    const ORDER_NO_LENGTH = 18;

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
                'title'           => $order_product['title'],
                'cover_image'     => $order_product['cover_image'],
                'attributes'      => $order_product['attributes'],
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
            return $order_no;
        }

        return Order::where('order_no', $order_no)->firstOrFail();
    }

    public static function queryOrderByChildOrderNo($order_no)
    {
        if ($order_no instanceof ChildOrder) {
            return $order_no;
        }

        return ChildOrder::where('order_no', $order_no)->firstOrFail();
    }

    public static function queryOrderById($order_id)
    {
        if ($order_id instanceof Order) {
            return $order_id;
        }

        return Order::findOrFail($order_id);
    }

    public static function queryFullOrder($order)
    {
        $relation = ['children', 'children.skus', 'address', 'billings', 'express'];
        if ($order instanceof Order) {
            return $order->load($relation);
        }

        return Order::with($relation)->where('id', $order)->first();
    }

    #todo 查询订单

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
        $order_no = substr(date('Y'), -2) . date('md') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));
        return $order_no;
    }


    public static function updateStatus($order_id, $status)
    {
        Order::where('id', $order_id)->update(['status' => $status]);
    }


    public static function updateStatusByOrderNo($order_no, $status)
    {
        $order = self::queryOrderByOrderNo($order_no);
        OrderProtocol::validStatus($order['status'], $status);

        return Order::where('order_no', $order_no)->update(['status' => $status]);
    }

    public static function deleteOrder($order_no)
    {
        $order = self::queryOrderByOrderNo($order_no);
        $order_full = self::queryFullOrder($order);

        self::updateStatus($order['id'], OrderProtocol::STATUS_OF_CANCEL);
        $order->delete();

        event(new OrderCancel($order_full));
    }

    public static function createChildOrder($merchant_order_info, $order_product_ids)
    {
        $merchant_order_info['order_no'] = '1' . self::generateOrderNo();
        $merchant_order_info['status'] = array_get($merchant_order_info, 'status', OrderProtocol::STATUS_OF_PAID);

        DB::transaction(function () use ($merchant_order_info, $order_product_ids) {
            $child_order = ChildOrder::updateOrCreate(
                ['order_id' => $merchant_order_info['order_id'], 'merchant_id' => $merchant_order_info['merchant_id']],
                array_only($merchant_order_info, ['order_id', 'order_no', 'merchant_id', 'total_amount', 'pay_amount', 'discount_fee', 'status'])
            );

            OrderProduct::whereIn('id', $order_product_ids)->update(['child_order_id' => $child_order['id']]);
        });
    }


    public static function updateOrderDeliver($deliver_id, $company_name, $post_no)
    {
        return OrderDeliver::where('id', $deliver_id)->update(
            [
                'company_name' => $company_name,
                'post_no'      => $post_no,
            ]
        );
    }

    public static function createOrderDeliver($company_name, $post_no)
    {
        return OrderDeliver::firstOrCreate(
            [
                'company_name' => $company_name,
                'post_no'      => $post_no,
            ]
        );
    }

    public static function deleteOrderDeliver($deliver_id)
    {
        return OrderDeliver::where('id', $deliver_id)->delete();
    }

    public static function updateChildOrderAsDeliver($order_no, $deliver_id)
    {
        return ChildOrder::where('order_no', $order_no)->update([
            'deliver_id' => $deliver_id,
            'status'     => OrderProtocol::STATUS_OF_DELIVER,
        ]);
        //商品关联发货信息
//        OrderProduct::whereIn('child_order_id', $child_order_ids)->update(['deliver_id' => $deliver_id]);
    }

    public static function updateChildOrderAsDeliverByOrder($order_no, $deliver_id)
    {
        return ChildOrder::where('order_no', $order_no)->update([
            'deliver_id' => $deliver_id,
            'status'     => OrderProtocol::STATUS_OF_DELIVER,
        ]);
        //商品关联发货信息
//        OrderProduct::whereIn('child_order_id', $child_order_ids)->update(['deliver_id' => $deliver_id]);
    }

    public static function updateOrderAsDeliver($order_no)
    {
        return self::updateStatusByOrderNo($order_no, OrderProtocol::STATUS_OF_DELIVER);
    }

    public static function updateOrderAsUnDeliver($order_no)
    {
        return self::updateStatusByOrderNo($order_no, OrderProtocol::STATUS_OF_PAID);
    }

    public static function expressCompany()
    {
        return ExpressHelper::expressCompany();
    }

    public static function getMainOrderNo($child_order_no)
    {
        $child_order = self::queryOrderByChildOrderNo($child_order_no);

        return $child_order->order->order_no;
    }

}
