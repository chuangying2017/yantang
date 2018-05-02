<?php

namespace App\Api\V1\Controllers\Client;

use App\Api\V1\Transformers\Subscribe\ChargeBillingTransformer;
use App\Repositories\Billing\ChargeBillingRepository;
use App\Services\Billing\ChargeBillingService;
use App\Services\Pay\Pingxx\PingxxPayService;
use App\Services\Pay\Pingxx\PingxxProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChargeController extends Controller {

    /**
     * @var ChargeBillingRepository
     */
    private $billingRepo;

    /**
     * ChargeController constructor.
     * @param ChargeBillingRepository $billingRepo
     */
    public function __construct(ChargeBillingRepository $billingRepo)
    {
        $this->billingRepo = $billingRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;
        $billings = $this->billingRepo->getAllBilling(access()->id(), $status);

        return $this->response->collection($billings, new ChargeBillingTransformer());
    }

    public function store(Request $request, PingxxPayService $payService, ChargeBillingService $billingService)
    {
        $amount = $request->input('amount');
        $channel = $request->input('channel') ?: PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT;

        $billing = $this->billingRepo->createBilling($amount, access()->id());

        $charge = $payService->setChannel($channel)->pay($billingService->setID($billing['id']));

        return $this->response->item($billing, new ChargeBillingTransformer())->setMeta(['charge' => $charge])->setStatusCode(201);
    }


}
