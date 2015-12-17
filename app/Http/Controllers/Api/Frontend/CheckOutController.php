<?php namespace App\Http\Controllers\Api\Frontend;

use App\Services\Orders\OrderProtocol;
use App\Services\Orders\OrderService;
use App\Services\Orders\Payments\CheckOut;
use App\Services\Orders\Supports\PingxxProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CheckOutController extends Controller {


    public function index($order_no)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $order = OrderService::show($user_id, $order_no);

            //若订单已支付则返回订单信息
            if ( ! Checkout::orderNeedPay($user_id, $order)) {
                return $this->response->array($order);
            }

            //订单未支付,返回订单数据和支付方式
            $agent = $this->getAgent();
            $channels = Checkout::channel($agent);

            return $this->response->array(compact('channels', 'order'));
        } catch (\Exception $e) {
            return $e->getTrace();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($order_no, Request $request)
    {

        try {

            $user_id = $this->getCurrentAuthUserId();
            //若订单已支付则返回订单信息
            if ( ! Checkout::orderNeedPay($user_id, $order_no)) {
                throw new \Exception('无需重复支付');
            }

            $channel = $request->input('channel', PingxxProtocol::PINGXX_SPECIAL_CHANNEL_WECHAT_QR);
            $agent = $this->getAgent();
            $data = Checkout::checkout($order_no, $channel, $agent);

            return $data;
        } catch (\Exception $e) {
            return $e->getTrace();
        }
    }


}
