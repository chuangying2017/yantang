<?php namespace App\Repositories\Order;

use App\Events\Order\OrderIsCancel;
use App\Events\Order\OrderIsDeliver;
use App\Events\Order\OrderIsDeliverDone;
use App\Events\Order\OrderIsDone;
use App\Events\Order\OrderIsPaid;
use App\Models\Order\Order;
use App\Repositories\Billing\OrderBillingRepository;
use App\Repositories\NoGenerator;
use App\Repositories\Order\Address\EloquentOrderAddressRepository;
use App\Repositories\Order\Memo\OrderMemoRepository;
use App\Repositories\Order\Promotion\OrderPromotionRepositoryContract;
use App\Repositories\Order\Sku\OrderSkuRepositoryContract;
use App\Services\Order\OrderProtocol;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientOrderRepository implements ClientOrderRepositoryContract {

    protected $type;
    /**
     * @var OrderSkuRepositoryContract
     */
    protected $orderSkuRepo;
    /**
     * @var EloquentOrderAddressRepository
     */
    protected $orderAddressRepo;
    /**
     * @var OrderBillingRepository
     */
    protected $orderBillingRepo;
    /**
     * @var OrderPromotionRepositoryContract
     */
    protected $orderPromotionRepo;
    /**
     * @var OrderMemoRepository
     */
    protected $memoRepo;

    protected $detail_relations = ['skus', 'address', 'billings'];
    protected $lists_relations = ['skus'];


    /**
     * ClientOrderRepositoryAbstract constructor.
     * @param OrderSkuRepositoryContract $orderSkuRepo
     * @param EloquentOrderAddressRepository $orderAddressRepo
     * @param OrderBillingRepository $orderBillingRepo
     */
    public function __construct(
        OrderSkuRepositoryContract $orderSkuRepo,
        EloquentOrderAddressRepository $orderAddressRepo,
        OrderBillingRepository $orderBillingRepo,
        OrderPromotionRepositoryContract $orderPromotionRepo,
        OrderMemoRepository $memoRepo
    )
    {
        $this->setOrderType();
        $this->orderSkuRepo = $orderSkuRepo;
        $this->orderAddressRepo = $orderAddressRepo;
        $this->orderBillingRepo = $orderBillingRepo;
        $this->orderPromotionRepo = $orderPromotionRepo;
        $this->memoRepo = $memoRepo;
    }

    protected function setOrderType()
    {
    }

    /**
     * @param $data
     */
    public function createOrder($data)
    {
        DB::beginTransaction();
        $order = Order::create([
            'user_id' => $data['user']['id'],
            'order_no' => NoGenerator::generateOrderNo(),
            'total_amount' => $data['total_amount'],
            'products_amount' => $data['products_amount'],
            'discount_amount' => $data['discount_amount'],
            'express_fee' => $data['express_fee'],
            'pay_amount' => $data['pay_amount'],
            'order_type' => $this->type,
            'pay_status' => OrderProtocol::PAID_STATUS_OF_UNPAID,
            'status' => OrderProtocol::STATUS_OF_UNPAID,
            'pay_type' => OrderProtocol::PAY_TYPE_OF_ONLINE,
            'deliver_type' => OrderProtocol::getDeliverType($this->type),
        ]);

        $order->skus = $this->orderSkuRepo->createOrderSkus($order, $data['skus']);
        $order->address = $this->createOrderAddress($order['id'], $data['address']);
        $order->billings = $this->createOrderBilling($order);
        $order->promotions = $this->createOrderPromotion($order['id'], $data['promotion']);
        $order->memo = $this->createOrderMemo($order['id'], array_get($data, 'memo', ''));


        DB::commit();

        return $order;
    }

    /**
     * @param $data
     * @param $order
     * @return mixed
     */
    protected function createOrderAddress($order_id, $address)
    {
        return $this->orderAddressRepo->createOrderAddress($order_id, $address);
    }

    protected function createOrderBilling($order, $wallet = null, $credits = null)
    {
        $billings = [
            OrderProtocol::BILLING_TYPE_OF_MONEY => $this->orderBillingRepo->setType(OrderProtocol::BILLING_TYPE_OF_MONEY)->createBilling($order['pay_amount'], $order['id'])
        ];
        return $billings;
    }

    protected function createOrderPromotion($order_id, $promotion)
    {
        return $this->orderPromotionRepo->createOrderPromotion($order_id, $promotion);
    }

    protected function createOrderMemo($order_id, $memo)
    {
        return $this->memoRepo->setCustomerMemo($order_id, $memo);
    }

    public function updateOrderStatus($order_no, $status)
    {
        $order = $this->getOrder($order_no, false);
        $order->status = $status;
        $order->save();
        return $order;
    }

    public function updateOrderPayStatus($order_no, $status, $pay_channel = null)
    {
        $order = $this->getOrder($order_no, false);
        $order->pay_status = $status;
        $order->pay_channel = $pay_channel;
        $order->save();

        return $order;
    }

	/**
     * @param $order_no
     * @param bool $with_detail
     * @return Order
     */
    public function getOrder($order_no, $with_detail = false)
    {
        if ($order_no instanceof Order) {
            $order = $order_no;
        } else if (NoGenerator::isOrderNo($order_no)) {
            $order = Order::query()->where('order_no', $order_no)->first();
        } else {
            $order = Order::query()->find($order_no);
        }

        if (!$order) {
            throw new ModelNotFoundException();
        }

        if ($with_detail) {
            $order = $order->load($this->detail_relations);
        }

        return $order;
    }

    public function getAllOrders($status = null, $order_by = 'created_at', $sort = 'desc')
    {
        $query = Order::where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with($this->lists_relations)->orderBy($order_by, $sort)->get();
    }

    public function getPaginatedOrders($status = null, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE)
    {
        $query = Order::where('order_type', $this->type);
        if (!is_null($status)) {
            $query = $query->where('status', $status);
        }
        return $query->with($this->lists_relations)->orderBy($order_by, $sort)->paginate($per_page);
    }

    public function updateOrderStatusAsPaid($order_id, $pay_channel)
    {
        $order = $this->updateOrderPayStatus($order_id, OrderProtocol::PAID_STATUS_OF_PAID, $pay_channel);
        $order = $this->updateOrderStatus($order, OrderProtocol::STATUS_OF_PAID);

        event(new OrderIsPaid($order));

        return $order;
    }

    public function updateOrderStatusAsDeliver($order_id)
    {
        $order = $this->updateOrderStatus($order_id, OrderProtocol::STATUS_OF_SHIPPING);

        event(new OrderIsDeliver($order));

        return $order;
    }

    public function updateOrderStatusAsDeliverDone($order_id)
    {
        $order = $this->updateOrderStatus($order_id, OrderProtocol::STATUS_OF_SHIPPED);

        event(new OrderIsDeliverDone($order));

        return $order;
    }

    public function updateOrderStatusAsDone($order_id)
    {
        $order = $this->updateOrderStatus($order_id, OrderProtocol::STATUS_OF_DONE);

        event(new OrderIsDone($order));

        return $order;
    }

    public function updateOrderStatusAsCancel($order_id)
    {
        $order = $this->updateOrderStatus($order_id, OrderProtocol::STATUS_OF_CANCEL);

        event(new OrderIsCancel($order));

        return $order;
    }

}
