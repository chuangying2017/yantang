<?php

namespace App\Http\Controllers;

use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Payments\BillingRepository;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class PingxxController extends Controller {

    public function callback(Request $request)
    {
        try {

            Log::info('Call back Start! input=' . json_encode($request->all()));

            if ($request->input('object') != 'event') {
                throw new Exception();
            }

            $data = $request->input('data.object');

            $billing_no = $data['order_no'];

            $type = $request->input('type');
            if ($type == 'charge.succeeded') {
                $billing = BillingRepository::fetchBilling($billing_no);
                if ( ! $billing) {
                    Log::error('Billing not exist. no=' . $billing_no . ' no need to continue');
                    exit("success");
                }
                $this->callbackChargeSucceed($data, $billing);
                // 更新操作日志
            } else if ($type == 'transfer.succeeded') {
                #todo 转账成功操作
            } else {
                Log::error('PingPP call back return unrecognized status = ' . $type);

                return 0;
            }

            // TODO 暂时不需要进行任何处理
            exit("success");
        } catch (Exception $e) {
            Log::error('Callback FAILED! throw exception, ' . $e);
            exit("fail");
        }
    }


    private function callbackFailed($request, $payment)
    {
        $payment['error_code'] = $request->input('failure_code');
        $payment['error_msg'] = $request->input('failure_msg');
        $payment->save();

        return;
    }

    private function callbackChargeSucceed($data, $billing)
    {

        if ($billing['status'] == OrderProtocol::STATUS_OF_PAID) {
            info('charge Succeed! no need to charge again');

            return 1;
        }

        $user_id = $billing['user_id'];
        $order_id = isset($data['extra']['order_id']) ? $data['extra']['order_id'] : 0;


        $billing['status'] = OrderProtocol::STATUS_OF_PAID;
        $billing['transaction_no'] = isset($data['transaction_no']) ? $data['transaction_no'] : '';


        if ($order_id) {
            $order = $this->orders->byId($order_id);

            $pay_result = $this->checkOut->payOrder($order['id']);
            if ($pay_result) {
                $this->orders->orderPaid($order, $pay_result['id']);

                $battery_record = $this->machineManager->lentBattery($order['machine_id'], $order_id, $user_id);
                event(new SendCommandToMachine(app('MachineCommand')->batteryOut($order['machine_id'])));

                return $battery_record;
            }
        }

        exit('success');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
