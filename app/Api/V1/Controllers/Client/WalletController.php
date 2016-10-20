<?php namespace App\Api\V1\Controllers\Client;

use App\Api\V1\Controllers\Controller;

use App\Api\V1\Transformers\Client\WalletRecordTransformer;
use App\Repositories\Client\Account\Wallet\WalletRepositoryContract;
use App\Services\Client\Account\AccountProtocol;
use Illuminate\Http\Request;

class WalletController extends Controller {

    /**
     * @var WalletRepositoryContract
     */
    private $walletRepo;

    /**
     * WalletController constructor.
     * @param WalletRepositoryContract $walletRepo
     */
    public function __construct(WalletRepositoryContract $walletRepo)
    {
        $this->walletRepo = $walletRepo;
    }

    public function index(Request $request)
    {
        $type = $request->input('type') ?: AccountProtocol::ACCOUNT_TYPE_RECHARGE;

        $wallet_records = $this->walletRepo->setUserId(access()->id())->getRecordsPaginated($type);

        return $this->response->paginator($wallet_records, new WalletRecordTransformer())->setMeta(['types' => AccountProtocol::accountRecordType()]);
    }

    public function balance()
    {
        $amount = $this->walletRepo->setUserId(access()->id())->getAmount();

        return $this->response->array(['data' => ['amount' => display_price($amount)]]);
    }


}
