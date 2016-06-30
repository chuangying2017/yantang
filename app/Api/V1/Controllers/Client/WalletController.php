<?php namespace App\Api\V1\Controllers\Client;

use App\API\V1\Controllers\Controller;
use App\Repositories\Client\Account\Wallet\WalletRepositoryContract;

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

    public function index()
    {
        
    }

}
