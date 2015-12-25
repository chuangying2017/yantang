<?php namespace App\Http\Controllers\Api\Frontend;

use App\Services\Orders\Exceptions\OrderAuthFail;
use App\Services\Orders\Exceptions\OrderIsPaid;
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

            //若订单已支付或者金额为0则返回订单信息
            if ( ! Checkout::orderNeedPay($user_id, $order)) {
                return $this->response->array($order);
            }

            //订单未支付,返回订单数据和支付方式
            $agent = $this->getAgent();
            $channels = Checkout::channel($agent);

            return $this->response->array(compact('channels', 'order'));
        } catch (OrderAuthFail $e) {
            $this->response->errorForbidden($e->getMessage());
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
    public function store(Request $request, $order_no)
    {

        try {
            $user_id = $this->getCurrentAuthUserId();
            //若订单已支付则返回订单信息
            if ( ! Checkout::orderNeedPay($user_id, $order_no)) {
                throw new OrderIsPaid();
            }

            $channel = $request->input('channel', PingxxProtocol::PINGXX_SPECIAL_CHANNEL_WECHAT_QR);
            $agent = $this->getAgent();
            $charge = Checkout::checkout($order_no, $channel, $agent);

            if ( ! $charge) {
                throw new \Exception('订单金额无需支付');
            }

            return $charge;
        } catch (OrderAuthFail $e) {
            $this->response->errorForbidden($e->getMessage());
        } catch (OrderIsPaid $e) {
            $this->response->errorBadRequest($e->getMessage());
        } catch (\Exception $e) {
            return $e->getTrace();
        }
    }


}
