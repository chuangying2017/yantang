<?php

namespace App\Api\V1\Controllers\Integral;


use App\Api\V1\Requests\Integral\OrderGenerateRequest;
use App\Api\V1\Transformers\Integral\ClientDetailTransformer;
use App\Api\V1\Transformers\Integral\ClientIntegralTransformer;

use App\Api\V1\Transformers\Integral\ClientOrderTransformer;
use App\Repositories\Client\Account\Wallet\EloquentWalletRepository;
use App\Repositories\Integral\OrderHandle\OrderIntegralInterface;
use App\Services\Client\Account\AccountProtocol;
use App\Services\Home\BannerService;
use App\Services\Integral\Product\ProductInerface;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ShowPageController extends Controller
{

    protected $product;

    protected $wallet;

    protected $orderIntegral;

    public function __construct(ProductInerface $productInerface,EloquentWalletRepository $contract,OrderIntegralInterface $orderIntegral)
    {
        $this->product                  = $productInerface;
        $this->wallet                   = $contract;
        $this->orderIntegral            = $orderIntegral;
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
     * @param OrderGenerateRequest $request
     * @return \Dingo\Api\Http\Response|void
     * @throws \ErrorException
     */
    public function store(OrderGenerateRequest $request)
    {

        $sore = $this->orderIntegral->order_generator($request->all());

        if(is_string($sore))
           return $this->response->array(['status'=>$sore]);

        return $this->response->noContent()->statusCode(200);

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

    /**
     * @api {get} /integral/showMemberOrder 参与记录
     * @apiName GetFrontDeskMeeting
     * @apiGroup FrontDesk
     *
     * @apiSuccess {statusCode} status 请求成功返回多维数组 | 或者返回空的数组
     * @apiError OrderDataNotFound this is order data not found 404
     */
    public function meeting_record()
    {
       $argc = $this->orderIntegral->user_order(['user_id' => access()->id()],\request()->input('page',1));

       return $this->response->collection($argc, new ClientOrderTransformer());
    }

}
