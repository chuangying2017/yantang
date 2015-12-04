<?php

namespace App\Http\Controllers;

use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Payments\BillingManager;
use App\Services\Orders\Payments\BillingRepository;
use App\Services\Orders\Supports\PingxxService;
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

            $pingxx_payment_id = $data['order_no'];

            $type = $request->input('type');
            if ($type == 'charge.succeeded') {
                $pingxx_payment = BillingRepository::fetchPingxxPayment($pingxx_payment_id);
                if ( ! $pingxx_payment) {
                    Log::error('Pingxx Payment not exist. no=' . $pingxx_payment_id . ' no need to continue');
                    exit("success");
                }
                $this->callbackChargeSucceed($data, $pingxx_payment);
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


    private function callbackFailed($request, $pingxx_payment_id)
    {
        $error_code = $request->input('failure_code');
        $error_msg = $request->input('failure_msg');

        PingxxService::pingxxPaymentPaidFail($pingxx_payment_id, $error_code, $error_msg);

        return;
    }

    private function callbackChargeSucceed($data, $pingxx_payment)
    {

        if ($pingxx_payment['status'] == OrderProtocol::STATUS_OF_PAID) {
            info('charge Succeed! no need to charge again');

            return 1;
        }

        $transaction_no = isset($data['transaction_no']) ? $data['transaction_no'] : '';

        PingxxService::pingxxPaymentIsPaid($pingxx_payment['id'], $transaction_no);

        event('App\Services\Orders\Event\PingxxPaid', [
            'order_id'          => isset($data['extra']['order_id']) ? $data['extra']['order_id'] : 0,
            'billing_id'        => isset($data['extra']['billing_id']) ? $data['extra']['billing_id'] : 0,
            'pingxx_payment_id' => $pingxx_payment['id'],
        ]);

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
