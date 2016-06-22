<?php namespace App\Api\V1\Controllers\Subscribe\Preorder;

use App\Api\V1\Controllers\Controller;
use App\Models\Subscribe\ChargeBilling;
use App\Services\Billing\PreorderBilling;
use App\Api\V1\Requests\Subscribe\TopUpRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\ChargeBillingTransformer;
use App\Services\Pay\Pingxx\PingxxPayService;
use Auth;
use App\Services\Billing\BillingProtocol;
use App\Repositories\Pay\PaymentRepositoryContract;
use Illuminate\Http\Request;
use App\Services\Client\Account\WalletService;
use Log;
use PreorderService;

class TopUpController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        PreorderService::settle();
//        $user_id = access()->id();
//        $charge_billing = ChargeBilling::where('user_id', $user_id);
//        return $this->response->item($charge_billing, new ChargeBillingTransformer);
    }

    public function store(TopUpRequest $request, PreorderBilling $preorderBilling, PingxxPayService $pingxxPayService)
    {
        $amount = $request->input('amount');
        $charge_billing = $preorderBilling->create($amount);
        $preorderBilling->setId($charge_billing->id);
        $pingxxPayService = $pingxxPayService->setChannel(BillingProtocol::BILLING_CHANNEL_OF_PREORDER_BILLING);
        $return = $pingxxPayService->pay($preorderBilling);
        return $this->response->array($return);
    }

    public function payConfirm(Request $request, PaymentRepositoryContract $paymentRepo, WalletService $walletService, PreorderBilling $preorderBilling)
    {
        $input = $request->all();
        if (!empty($input) && $input['type'] == "charge.succeeded") {
            $payment_no = $input['id'];
            $transaction_no = $input['data']['transaction_no'];
            $payment = $paymentRepo->setPaymentAsPaid($payment_no, $transaction_no);
            $charge_billing = ChargeBilling::where('billing_no', $input['billing_no'])->first();
            $preorderBilling->setId($charge_billing->id);
            $walletService->pay($preorderBilling);
        } else {
            //todo 出错写入日志
        }

    }
}