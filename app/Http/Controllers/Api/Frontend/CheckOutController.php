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

            if ( ! OrderService::orderNeedPay($user_id, $order)) {
                return $this->response->array($order);
            }

            $agent = $this->getAgent();
            $channels = $this->checkOut->channel($agent);

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
            $channel = $request->input('channel', PingxxProtocol::PINGXX_SPECIAL_CHANNEL_WECHAT_QR);

            #todo 判断支付请求来源
            $agent = $this->getAgent();

            $data = Checkout::checkout($order_no, $channel, $agent);

            return $data;
        } catch (\Exception $e) {
            return $e->getTrace();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
