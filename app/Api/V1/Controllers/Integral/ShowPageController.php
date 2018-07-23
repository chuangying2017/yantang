<?php

namespace App\Api\V1\Controllers\Integral;


use App\Api\V1\Transformers\Integral\ClientDetailTransformer;
use App\Api\V1\Transformers\Integral\ClientIntegralTransformer;
use App\Models\Access\User\User;
use App\Repositories\Client\Account\EloquentAccountRepository;
use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Repositories\Client\Account\Wallet\WalletRepositoryContract;
use App\Repositories\Integral\OrderFilter\OrderFilter;
use App\Services\Client\Account\AccountProtocol;
use App\Services\Home\BannerService;
use App\Services\Integral\Product\ProductInerface;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShowPageController extends Controller
{

    protected $product;

    protected $wallet;

    protected $filterOrder;

    public function __construct(ProductInerface $productInerface,EloquentWalletRepository $contract,OrderFilter $orderFilter)
    {
        $this->product                  = $productInerface;
        $this->wallet                   = $contract;
        $this->filterOrder              = $orderFilter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        $product_ = $this->product->get_all_product(['status' => 'up'], false, 'sort_type', 'asc');
        $product_->load('product_sku');

        if(!$access=access()->id()) throw new \Exception('用戶信息不存在 not exiting user messages',500);

        return $this
            ->response
            ->collection($product_, new ClientIntegralTransformer())
            ->setMeta([
                'banner'=>BannerService::listByType('integral')->toArray(),
                'walletIntegral'=>$this->wallet->setUserId($access)->getAmount(AccountProtocol::ACCOUNT_AMOUNT_INTEGRAL)
            ]);
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
        $product_one_data = $this->product->get_product($id);

        return $this->response->item($product_one_data, new ClientDetailTransformer());
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
