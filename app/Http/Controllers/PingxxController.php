<?php

namespace App\Http\Controllers;

use App\Services\Orders\OrderProtocol;
use App\Services\Orders\Supports\PingxxPaymentRepository;
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

            Log::info('Pingxx Call back Start! input=' . json_encode($request->all()));

            if ($request->input('object') != 'event') {
                throw new Exception();
            }

            $data = $request->input('data.object');

            $pingxx_payment_no = $data['order_no'];
            $channel = $data['channel'];

            $type = $request->input('type');
            if ($type == 'charge.succeeded') {
                $channel = $data['channel'];
                $pingxx_payment = PingxxPaymentRepository::getPingxxChannelPayment($pingxx_payment_no, $channel);
                if ( ! $pingxx_payment) {
                    Log::error('Pingxx Payment not exist. no=' . $pingxx_payment_no . ' no need to continue');
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


    private function callbackFailed($request, $pingxx_payment_no)
    {
        $error_code = $request->input('failure_code');
        $error_msg = $request->input('failure_msg');

        PingxxService::pingxxPaymentPaidFail($pingxx_payment_no, $error_code, $error_msg);

        return;
    }

    private function callbackChargeSucceed($data, $pingxx_payment)
    {

        if ($pingxx_payment['status'] == OrderProtocol::STATUS_OF_PAID) {
            info('charge Succeed! no need to charge again');
            return 1;
        }

        PingxxService::pingxxPaymentIsPaid($data);
        exit('success');
    }

}
