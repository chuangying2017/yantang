<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Transformers\OrderTransformer;
use App\Services\Cart\CartService;
use App\Services\Client\AddressService;
use App\Services\Orders\Exceptions\WrongStatus;
use App\Services\Orders\OrderGenerator;
use App\Services\Orders\OrderService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller {

    /**
     * @var OrderGenerator
     */
    private $orderGenerator;

    /**
     * OrderController constructor.
     * @param OrderGenerator $orderGenerator
     */
    public function __construct(OrderGenerator $orderGenerator)
    {
        $this->orderGenerator = $orderGenerator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = $this->getCurrentAuthUserId();

        $orders = OrderService::lists($user_id);


        return $this->response->collection($orders, new OrderTransformer());
    }

    public function preConfirm(Request $request)
    {
        try {
            $carts = $request->input('data');
            $user_id = $this->getCurrentAuthUserId();
            $order_info = $this->orderGenerator->buyCart($user_id, $carts);

            return $order_info;
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
    }

    public function fetchConfirm(Request $request)
    {
        $uuid = $request->input('uuid');

        $order_info = OrderGenerator::getOrder($uuid);
        $user_id = $this->getCurrentAuthUserId();

        if ($user_id != $order_info['user_id']) {
            $this->response->errorForbidden();
        }

        return self::transformOrder($order_info);
    }

    public static function transformOrder($order_info)
    {
        $order_info['total_amount'] = display_price($order_info['total_amount']);
        $order_info['pay_amount'] = display_price($order_info['pay_amount']);
        $order_info['discount_fee'] = display_price($order_info['discount_fee']);
        if (isset($order_info['products'])) {
            foreach ($order_info['products'] as $key => $product) {
                $product['attributes'] = json_decode('[' . $product['attributes'] . ']', true);
                $product['price'] = display_price($product['price']);
                $order_info['products'][ $key ] = $product;
            }
        }

        return $order_info;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $address_id = $request->input('address_id');
            $uuid = $request->input('uuid');
            $memo = $request->input('memo', '');
            $user_id = $this->getCurrentAuthUserId();

            $order = $this->orderGenerator->confirm($uuid, $address_id);

            return self::transformOrder($order);

            return $this->response->created(route('api.orders.show', $order['order_no']))->setMeta($order);
        } catch (\Exception $e) {
            return $e->getTrace();
            $this->response->errorInternal($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($order_no)
    {
        $user_id = $this->getCurrentAuthUserId();
        try {
            $order = OrderService::show($user_id, $order_no);
            $order->show_full = 1;


        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->item($order, new OrderTransformer());
    }


    public function destroy($order_no)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            OrderService::delete($user_id, $order_no);
        } catch (WrongStatus $e) {
            $this->response->errorForbidden($e->getMessage());
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

        return $this->response->noContent();
    }


}
