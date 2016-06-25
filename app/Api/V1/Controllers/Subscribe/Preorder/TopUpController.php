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
use App\Repositories\Client\Account\Wallet\WalletRepositoryContract;
use App\Api\V1\Transformers\Subscribe\Preorder\WalletRecordTransformer;

class TopUpController extends Controller
{
    const PER_PAGE = 10;

    public function __construct()
    {

    }

    public function index(Request $request, WalletRepositoryContract $walletRepo)
    {
        $per_page = $request->input('paginate', self::PER_PAGE);
        $user_id = access()->id();
        $walletRepo = $walletRepo->setUserId($user_id);
        $wallet = $walletRepo->getRecordsPaginated(BillingProtocol::BILLING_TYPE_OF_RECHARGE_BILLING, 'created_at', 'desc', $per_page);
        return $this->response->paginator($wallet, new WalletRecordTransformer());
    }

    public function userAmount(WalletRepositoryContract $walletRepo)
    {
        $user_id = access()->id();
        $walletRepo = $walletRepo->setUserId($user_id);
        $amount = $walletRepo->getAmount();
        return $this->response->array(['data' => ['amount' => $amount]]);
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
    
}