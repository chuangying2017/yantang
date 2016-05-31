<?php namespace App\Api\V1\Controllers\Subscribe\Preorder;

use App\Api\V1\Controllers\Controller;
use App\Models\Subscribe\ChargeBilling;
use App\Services\Billing\PreorderBilling;
use App\Api\V1\Requests\Subscribe\TopUpRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\ChargeBillingTransformer;
use App\Services\Pay\Pingxx\PingxxPayService;
use Auth;

class TopUpController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $user_id = Auth::user()->id();
        $charge_billing = ChargeBilling::where('user_id', $user_id);
        return $this->response->item($charge_billing, new ChargeBillingTransformer);
    }

    public function store(TopUpRequest $request, PreorderBilling $preorderBilling, PingxxPayService $pingxxPayService)
    {
        $amount = $request->input('amount');
        $charge_billing = $preorderBilling->create($amount);
        $preorderBilling->setId($charge_billing->id);
        $return = $pingxxPayService->pay($preorderBilling);
        return $this->response->array($return);
    }
}