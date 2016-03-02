<?php namespace App\Http\Controllers\Api\Frontend;

use App\Library\Wechat\WechatService;
use App\Services\Orders\Exceptions\OrderAuthFail;
use App\Services\Orders\Exceptions\OrderIsPaid;
use App\Services\Orders\OrderService;
use App\Services\Orders\Payments\BillingManager;
use App\Services\Orders\Payments\CheckOut;
use App\Services\Orders\Supports\PingxxProtocol;
use App\Services\Utilities\HttpHelper;
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
                $order = OrderService::show($user_id, $order_no);

                return $this->response->array(['order' => self::transformOrder($order)]);
            }

            //订单未支付,返回订单数据和支付方式
            $agent = $this->getAgent();
            $channels = Checkout::channel($agent);

            return $this->response->array(['channels' => $channels, 'order' => self::transformOrder($order)]);
        } catch (OrderAuthFail $e) {
            $this->response->errorForbidden($e->getMessage());
        } catch (\Exception $e) {
            return $e->getTrace();
        }
    }

    protected static function transformOrder($order)
    {
        $order['total_amount'] = display_price(array_get($order, 'total_amount'));
        $order['discount_fee'] = display_price(array_get($order, 'discount_fee'));
        $order['pay_amount'] = display_price(array_get($order, 'pay_amount'));
        $order['post_fee'] = display_price(array_get($order, 'post_fee'));

        return $order;
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

            $channel = $request->input('channel', PingxxProtocol::PINGXX_SPECIAL_CHANNEL_WECHAT_QR);
            PingxxProtocol::validChannel($channel);


            #TODO REMOVE TEST PAY
//            $channel = $request->input('channel', PingxxProtocol::PINGXX_SPECIAL_CHANNEL_WECHAT_QR);
//            PingxxProtocol::validChannel($channel);
//
//            $charge = Checkout::checkout($order_no, $channel);
//
//            if ( ! $charge) {
//                throw new OrderIsPaid();
//            }
//
//            $url = 'https://api.pingxx.com/notify/charges/' . $charge->id . '?livemode=false';
//
//            $res = HttpHelper::http_get($url);
            #TODO END REMOVE TEST PAY

            //若订单已支付则返回订单信息
            if ( ! Checkout::orderNeedPay($user_id, $order_no)) {
                #TODO REMOVE TEST PAY
//                return $this->response->array(['data' => $res]);
                #TODO END REMOVE TEST PAY
                throw new OrderIsPaid();
            }


            $charge = Checkout::checkout($order_no, $channel);


            if ( ! $charge) {
                throw new OrderIsPaid();
            }

            return $this->response->array($charge);
        } catch (OrderAuthFail $e) {
            $this->response->errorForbidden($e->getMessage());
        } catch (OrderIsPaid $e) {
            $this->response->errorBadRequest($e->getMessage());
        } catch (\Exception $e) {
            return $e->getTrace();
        }
    }


}
