<?php namespace App\Services\Orders;

use App\Models\ChildOrder;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Models\OrderRefund;
use App\Services\Orders\Event\OrderCancel;
use App\Services\Orders\Event\OrderDone;
use App\Services\Orders\Helpers\ExpressHelper;
use App\Models\OrderDeliver as Deliver;
use App\Services\Orders\Supports\PingxxPaymentRepository;
use Carbon\Carbon;
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


    public static function queryChildOrderByOrderNo($order_no)
    {
        if ($order_no instanceof ChildOrder) {
            return $order_no;
        }

        return ChildOrder::where('order_no', $order_no)->firstOrFail();
    }

    public static function queryChildOrderOfMainOrderById($order_id)
    {
        return ChildOrder::where('order_id', $order_id)->get();
    }

    public static function queryOrderMerchants($order_id)
    {
        return ChildOrder::where('order_id', $order_id)->select('merchant_id')->distinct()->get()->toArray();
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
        $relation = ['children', 'children.skus', 'children.deliver', 'address', 'billings', 'refund', 'refund.products'];
        if ($order instanceof Order) {
            $order = $order->load($relation);
        } else {
            $order = Order::with($relation)->where('order_no', $order)->first();
        }

        $order->payment = PingxxPaymentRepository::getOrderPaidPayment($order['id']);

        return $order;
    }

    public static function lists($user_id = null, $sort_by = 'created_at', $sort_type = 'desc', $relation = null, $status = null, $paginate = null, $merchant_id = null, $keyword = null, $start_at = null, $end_at = null)
    {

        if ( ! is_null($relation)) {
            $query = Order::with($relation)->orderBy($sort_by, $sort_type);
        } else {
            $query = Order::orderBy($sort_by, $sort_type);
        }

        if ( ! is_null($status)) {
            $query = $query->whereHas('children', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        if ( ! is_null($merchant_id) && $merchant_id) {
            $query = $query->whereHas('children', function ($query) use ($merchant_id) {
                $query->where('merchant_id', $merchant_id);
            });
        }

        if ( ! is_null($user_id)) {
            $query = $query->where('user_id', $user_id);
        }

        if ( ! is_null($keyword)) {
            $query = $query->where(function ($query) use ($keyword) {
                $query->where('order_no', $keyword)
                    ->orWhereHas('children', function ($query) use ($keyword) {
                        $query->where('order_no', $keyword);
                    })->orWhereHas('address', function ($query) use ($keyword) {
                        $query->where(function ($query) use ($keyword) {
                            $query->where('mobile', '=', $keyword)
                                ->orWhere('name', '=', $keyword);
                        });
                    })->orWhere('title', 'like', '%' . $keyword . '%');
            });
        }

        if ( ! is_null($start_at) || ! is_null($end_at)) {
            $start_at = is_null($start_at) ? Carbon::createFromDate(2016, 1, 1) : Carbon::createFromTimestamp(strtotime($start_at));
            $end_at = is_null($end_at) ? Carbon::now() : Carbon::createFromTimestamp(strtotime($end_at));
            $query = $query->whereBetween('created_at', [$start_at, $end_at]);
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
        $update_data = ['status' => $status];
        if ($status == OrderProtocol::STATUS_OF_PAID) {
            $update_data['pay_at'] = Carbon::now();
        }

        if ($status == OrderProtocol::STATUS_OF_DELIVER) {
            $update_data['deliver_at'] = Carbon::now();
        }

        if ($status == OrderProtocol::STATUS_OF_CANCEL) {
            $update_data['cancel_at'] = Carbon::now();
        }

        Order::where('id', $order_id)->update($update_data);

        $need_change_child_order = [
            OrderProtocol::STATUS_OF_CANCEL,
            OrderProtocol::STATUS_OF_PAID,
            OrderProtocol::STATUS_OF_DELIVER,
            OrderProtocol::STATUS_OF_DONE,
        ];

        if (in_array($status, $need_change_child_order)) {
            ChildOrder::where('order_id', $order_id)->update(['status' => $status]);
        }

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

    public static function doneOrder($order_no)
    {
        $order = self::queryOrderByOrderNo($order_no);

        self::updateStatus($order['id'], OrderProtocol::STATUS_OF_DONE);

        event(new OrderDone(self::queryFullOrder($order)));
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
        return Deliver::where('id', $deliver_id)->update(
            [
                'company_name' => $company_name,
                'post_no'      => $post_no,
            ]
        );
    }

    public static function createOrderDeliver($company_name, $post_no)
    {
        return Deliver::firstOrCreate(
            [
                'company_name' => $company_name,
                'post_no'      => $post_no,
            ]
        );
    }

    public static function deleteOrderDeliver($deliver_id)
    {
        return Deliver::where('id', $deliver_id)->delete();
    }

    public static function updateChildOrderAsDeliver($order_no, $deliver_id)
    {

        $status = OrderProtocol::STATUS_OF_DELIVER;

        return ChildOrder::where('order_no', $order_no)->update([
            'deliver_id' => $deliver_id,
            'status'     => $status,
        ]);
        //商品关联发货信息
//        OrderProduct::whereIn('child_order_id', $child_order_ids)->update(['deliver_id' => $deliver_id]);
    }

    public static function updateChildOrderAsDeliverByOrder($order_id, $deliver_id)
    {

        $status = OrderProtocol::STATUS_OF_DELIVER;

        return ChildOrder::where('order_id', $order_id)->update([
            'deliver_id' => $deliver_id,
            'status'     => $status,
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
        $child_order = self::queryChildOrderByOrderNo($child_order_no);

        return $child_order->order->order_no;
    }

    public static function updateOrderRefund($order, $status, $amount = 0)
    {
        $order = self::queryOrderByOrderNo($order);
        $order['refund_status'] = $status;
        if ($amount > 0) {
            $order['refund_amount'] = $order['refund_amount'] + $amount;
        }
        $order->save();

        return $order;
    }

    public static function queryOrderProduct($order_product_id)
    {
        if (is_array($order_product_id)) {
            return OrderProduct::whereIn('id', $order_product_id)->get();
        }

        return OrderProduct::findOrFail($order_product_id);
    }

    public static function updateOrderProductRefund($order_product_id, $status, $quantity = 0, $amount = 0)
    {
        $order_product = self::queryOrderProduct($order_product_id);
        $order_product['return_quantity'] = $order_product['return_quantity'] + $quantity;
        $order_product['refund_amount'] = $order_product['refund_amount'] + $amount;
        $order_product['refund_status'] = $status;
        $order_product->save();

        return $order_product;
    }


    public static function queryOrderProductByOrder($order_id)
    {
        return OrderProduct::where('order_id', $order_id)->get();
    }


    /**********************************************************************
     *
     * Refund Orders
     *
     * ********************************************************************
     */
    public static function createRefundOrder($data)
    {
        return OrderRefund::create($data);
    }

    public static function showRefundOrder($order_id)
    {
        return OrderRefund::with('products')->findOrFail($order_id);
    }

    public static function listsRefundOrder($status = OrderProtocol::STATUS_OF_RETURN_APPLY, $paginate = 20)
    {
        $query = OrderRefund::where('status', $status);

        if ( ! is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    public static function fetchRefundOrder($refund_order)
    {
        if ($refund_order instanceof OrderRefund) {
            return $refund_order;
        }

        return OrderRefund::findOrFail($refund_order);
    }

    public static function updateRefundOrderStatus($refund_order_id, $status, $memo = null)
    {
        $refund_order = self::fetchRefundOrder($refund_order_id);

        $refund_order['status'] = $status;
        if ( ! is_null($memo)) {
            $refund_order['merchant_memo'] = $memo;
        }
        $refund_order->save();
        Order::where('id', $refund_order['order_id'])->update(['refund_status' => $status]);

        return 1;
    }


    public static function updateRefundOrderDeliver($refund_order_id, $company_name, $post_no)
    {
        $refund_order = self::fetchRefundOrder($refund_order_id);
        $refund_order['company_name'] = $company_name;
        $refund_order['post_no'] = $post_no;
        $status = OrderProtocol::STATUS_OF_RETURN_DELIVER;
        $refund_order['status'] = $status;
        $refund_order['deliver_at'] = Carbon::now();
        $refund_order->save();

        Order::where('id', $refund_order['order_id'])->update(['refund_status' => $status]);

        return $refund_order;
    }

}
