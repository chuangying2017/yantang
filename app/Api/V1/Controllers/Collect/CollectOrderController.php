<?php

namespace App\Api\V1\Controllers\Collect;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Collect\CollectOrderRequest;
use App\Api\V1\Requests\Collect\UpdateCollectOrderRequest;
use App\Api\V1\Requests\Collect\DeleteCollectOrderRequest;
use App\Api\V1\Requests\Collect\ConfirmCollectRequest;
use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Api\V1\Transformers\CollectOrderTransformer;
use App\Api\V1\Transformers\TempOrderTransformer;
use App\Services\Order\CollectOrderGenerator;
use App\Services\Order\CollectOrderProtocol;
use App\Services\Preorder\PreorderProtocol;
use App\Services\Pay\Pingxx\PingxxProtocol;
use App\Services\Order\OrderProtocol;
use App\Repositories\Address\AddressRepositoryContract;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;

use App\Services\Order\Checkout\OrderCheckoutService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Models\Collect\CollectOrder;

use App\Http\Requests;
use Carbon\Carbon;
use Pinyin;

class CollectOrderController extends Controller {

    /**
     * @var AddressRepositoryContract
     */
    private $addressRepo;

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    public function __construct(AddressRepositoryContract $addressRepo,CouponRepositoryContract $couponRepo)
    {
        $this->addressRepo = $addressRepo;
        $this->couponRepo = $couponRepo;
    }

    public function index( Request $requset )
    {
        $status = $requset->input('status');
        $orders = CollectOrder::query()
                ->with('order')
                ->where('staff_id',access()->id());
        if( $status == 'collected'){
            $orders->whereHas('order', function( $query ){
                $query->where('pay_status', OrderProtocol::PAID_STATUS_OF_PAID );
            });
        }
        else{
            $pay_status = [
                OrderProtocol::PAID_STATUS_OF_UNPAID,
                OrderProtocol::PAID_STATUS_OF_PARTIAL,
                OrderProtocol::PAID_STATUS_OF_PENDING,
            ];
            $orders->where(function($query) use ($pay_status){
                $query->whereHas('order', function( $query ) use ($pay_status){
                    $query->whereIn('pay_status', $pay_status);
                })
                ->orWhereNull('order_id');
            });
        }
        $orders = $orders->orderBy('created_at', 'desc')
            ->paginate(CollectOrderProtocol::ORDER_PER_PAGE);

        return $this->response->paginator($orders, new CollectOrderTransformer());
    }

    public function store(CollectOrderRequest $request, CollectOrderGenerator $collectOrderGenerator)
    {
        $sku_id = $request->input('sku_id');
        $quantity = $request->input('quantity');
        $address_id = $request->input('address_id');

        $temp_order = CollectOrder::create([
            'sku_id' => $sku_id,
            'quantity' => $quantity,
            'address_id' => $address_id,
            'staff_id' => access()->id()
        ]);
        return $this->response->noContent()->setStatusCode(201);
    }

    public function preConfirm(CollectOrder $collect_order, ConfirmCollectRequest $request, CollectOrderGenerator $collectOrderGenerator)
    {

        $weekday_type = 'all';
        $daytime = PreorderProtocol::DAYTIME_OF_AM;
        $start_time = Carbon::today()->toDateString();
        $skus = [[
            'product_sku_id' => $collect_order['sku_id'],
            'quantity' => $collect_order['quantity'],
            'per_day' => $collect_order['quantity'],
        ]];
        $address_id = $collect_order['address_id'];
        try {
            $this->addressRepo->transferAddressToUser( $address_id, access()->id() );

            $temp_order = $collectOrderGenerator->subscribe(access()->id().','.$collect_order['id'], $skus, $weekday_type, $daytime, $start_time, $address_id );

            return $this->response->item($temp_order, new TempOrderTransformer());
        } catch (ModelNotFoundException $e) {
            $this->response->errorNotFound($e->getMessage());
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }

    public function confirm(CollectOrder $collect_order, ConfirmCollectRequest $request, CollectOrderGenerator $collectOrderGenerator, OrderCheckoutService $orderCheckout)
    {
        $temp_order_id = $request->input('temp_order_id');
        $staff_id = $collect_order['staff_id'];
        $collect_order_id = $collect_order['id'];

        $pay_channel = $request->input('channel') ?: PingxxProtocol::PINGXX_WAP_CHANNEL_WECHAT;
        try {
            $order = $collectOrderGenerator->confirmSubscribe($temp_order_id);

            //收款员逻辑
            if( !is_null( $staff_id ) ){
                CollectOrder::where([
                    'id' => $collect_order_id,
                    'staff_id' => $staff_id,
                ])->update([
                    'order_id'=> $order['id'],
                ]);
            }

            $charge = $orderCheckout->checkout($order['id'], OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel);

            return $this->response->item($order, new ClientOrderTransformer())->setMeta(['charge' => $charge])->setStatusCode(201);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(CollectOrder $collect_order)
    {
        return $this->response->item($collect_order, new CollectOrderTransformer());
    }

    public function update(CollectOrder $collect_order, UpdateCollectOrderRequest $request, CollectOrderGenerator $collectOrderGenerator)
    {

        $sku_id = $request->input('sku_id');
        $quantity = $request->input('quantity');
        $address_id = $request->input('address_id');


        $collect_order->update([
            'sku_id' => $sku_id,
            'quantity' => $quantity,
            'address_id' => $address_id,
        ]);

        return $this->response->item($collect_order, new CollectOrderTransformer());

    }

    public function destroy(CollectOrder $collect_order, DeleteCollectOrderRequest $request, CollectOrderGenerator $collectOrderGenerator)
    {
        $collect_order->delete();

        return $this->response->noContent();
    }

    public function addresses(){
        $collectedOrders = CollectOrder::getAddressIds()->toArray();
        $addresses = array_column($collectedOrders, 'address');

        $addrByInitialChar = [];
        foreach( $addresses as $address ){
            $firstChar = mb_substr($address['detail'], 0, 1);
            $firstCharPinyin = Pinyin::convert( $firstChar )[0];
            $firstCharPinyinInitial = substr($firstCharPinyin, 0, 1);
            $address['detailFullPinyin'] = Pinyin::sentence($address['detail']);
            $addrByInitialChar[strtoupper($firstCharPinyinInitial)][] = $address;
        }
        $data = [
            'addresses' => $addrByInitialChar,
        ];
        return $this->response->array($data);
    }

}
