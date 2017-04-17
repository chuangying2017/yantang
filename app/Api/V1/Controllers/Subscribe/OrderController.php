<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\SubscribeOrderRequest;
use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Api\V1\Transformers\TempOrderTransformer;
use App\Repositories\Order\PreorderOrderRepository;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Order\OrderGenerator;
use App\Services\Order\OrderManageContract;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Subscribe\CollectOrder;
use Illuminate\Http\Request;

use App\Http\Requests;

class OrderController extends Controller {

    /**
     * @var PreorderOrderRepository
     */
    private $orderRepo;

    /**
     * OrderController constructor.
     * @param PreorderOrderRepository $orderRepo
     */
    public function __construct(PreorderOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index()
    {
        $orders = $this->orderRepo->getPaginatedOrders();

        return $this->response->paginator($orders, new ClientOrderTransformer());
    }

    public function store(SubscribeOrderRequest $request, OrderGenerator $orderGenerator, OrderCheckoutService $orderCheckout)
    {
        try {
            $skus = $request->input('skus');
            $weekday_type = $request->input('weekday_type');
            $daytime = $request->input('daytime');
            $start_time = $request->input('start_time');
            $address_id = $request->input('address_id');

            $temp_order = $orderGenerator->subscribe(access()->id(), $skus, $weekday_type, $daytime, $start_time, $address_id);

            return $this->response->item($temp_order, new TempOrderTransformer());
        } catch (ModelNotFoundException $e) {
            $this->response->errorNotFound($e->getMessage());
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }

    public function confirm($temp_order_id, Request $request, OrderGenerator $orderGenerator, OrderCheckoutService $orderCheckout)
    {
        $staff_id = $request->input('staff_id');
        $collect_order_id = $request->input('collect_order_id');
        try {

            $pay_channel = $request->input('channel') ?: PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT;

            $order = $orderGenerator->confirmSubscribe($temp_order_id);

            //收款员逻辑
            if( !is_null( $staff_id ) ){
                CollectOrder::where([
                    'id' => $collect_order_id,
                    'staff_id' => $staff_id,
                    'has_generated_preorder' => 0,
                ])->update([
                    'preorder_id'=> $order['id'],
                    'has_generated_preorder' => 1,
                ]);
            }

            $charge = $orderCheckout->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

            return $this->response->item($order, new ClientOrderTransformer())->setMeta(['charge' => $charge])->setStatusCode(201);;
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->orderRepo->getOrder($id, true);

        return $this->response->item($order, new ClientOrderTransformer());
    }

    public function view( $temp_order_id, OrderGenerator $orderGenerator ){
        $temp_order = $orderGenerator->getTempOrder( $temp_order_id );
        return $this->response->item($temp_order, new TempOrderTransformer());
    }

    public function update($temp_order_id, Request $request, OrderGenerator $orderGenerator)
    {

        $ticket_id = $request->input('ticket') ?: null;

        $skus = $request->input('skus');
        $address_id = $request->input('address_id');

        $temp_order = $orderGenerator->getTempOrder($temp_order_id);
        if ($ticket_id) {
            $temp_order = $orderGenerator->useCoupon($temp_order_id, $ticket_id);
        }
        if( $skus ){
            $temp_order = $orderGenerator->updateSku($temp_order_id, $skus);
        }
        if( $address_id ){
            $temp_order = $orderGenerator->updateAddress($temp_order_id, $address_id);
        }

        $temp_order = $orderGenerator->getTempOrder($temp_order_id);

        return $this->response->item($temp_order, new TempOrderTransformer());

    }

    public function remove($temp_order_id, OrderGenerator $orderGenerator)
    {
        $order = $orderGenerator->pullTempOrder($temp_order_id);

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderManageContract $orderManage, Request $request, $id)
    {
        $orderManage->orderCancel($id, $request->input('memo'), $request->input('order_skus'));

        return $this->response->noContent();
    }

}
