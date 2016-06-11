<?php namespace App\Repositories\Order;

use App\Models\Order\Order;
use App\Repositories\Billing\OrderBillingRepository;
use App\Repositories\NoGenerator;
use App\Repositories\Order\Address\EloquentOrderAddressRepository;
use App\Repositories\Order\Promotion\OrderPromotionContract;
use App\Repositories\Order\Sku\OrderSkuRepositoryContract;
use App\Services\Order\OrderProtocol;
use DB;

abstract class ClientOrderRepositoryAbstract implements ClientOrderRepositoryContract {

    protected $type;
    /**
     * @var OrderSkuRepositoryContract
     */
    private $orderSkuRepo;
    /**
     * @var EloquentOrderAddressRepository
     */
    private $orderAddressRepo;
    /**
     * @var OrderBillingRepository
     */
    private $orderBillingRepo;
    /**
     * @var OrderPromotionContract
     */
    private $orderPromotionRepo;


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
        OrderPromotionContract $orderPromotionRepo
    )
    {
        $this->setOrderType();
        $this->orderSkuRepo = $orderSkuRepo;
        $this->orderAddressRepo = $orderAddressRepo;
        $this->orderBillingRepo = $orderBillingRepo;
        $this->orderPromotionRepo = $orderPromotionRepo;
    }

    protected abstract function setOrderType();

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
            'product_amount' => $data['product_amount'],
            'discount_amount' => $data['discount_amount'],
            'express_fee' => $data['express_fee'],
            'pay_amount' => $data['pay_amount'],
            'order_type' => $this->type,
            'pay_status' => OrderProtocol::PAID_STATUS_OF_UNPAID,
            'order_status' => OrderProtocol::STATUS_OF_UNPAID,
            'pay_type' => OrderProtocol::PAY_TYPE_OF_ONLINE,
            'deliver_type' => OrderProtocol::getDeliverType($this->type)
        ]);

        $order->order_skus = $this->createOrderSkus($order['id'], $data['skus']);
        $order->order_address = $this->createOrderAddress($order['id'], $data['address']);
        $order->order_billing = $this->createOrderBilling($order);
        $order->order_promotion = $this->createOrderPromotion($order['id'], $data['promotion']);

        DB::commit();

        return $order;
    }

    protected function createOrderSkus($order_id, $skus)
    {
        return $this->orderSkuRepo->createOrderSkus($order_id, $skus);
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
            OrderProtocol::PAY_TYPE_OF_ONLINE => $this->orderBillingRepo->setType(OrderProtocol::PAY_TYPE_OF_ONLINE)->createBilling($order['pay_amount'], $order['id'])
        ];

        return $billings;
    }

    protected function createOrderPromotion($order_id, $promotion)
    {
        return $this->orderPromotionRepo->createOrderPromotion($order_id, $promotion);
    }

    public function updateOrderStatus($order_no, $status)
    {

    }

    public function updateOrderPayStatus($order_no, $status, $pay_channel = null)
    {
        // TODO: Implement updateOrderPayStatus() method.
    }

    public function getOrder($order_no)
    {
        // TODO: Implement getOrder() method.
    }

    public function getAllOrders($status, $order_by = 'created_at', $sort = 'desc')
    {
        // TODO: Implement getAllOrders() method.
    }

    public function getPaginatedOrders($status, $order_by = 'created_at', $sort = 'desc', $per_page = OrderProtocol::ORDER_PER_PAGE)
    {
        // TODO: Implement getPaginatedOrders() method.
    }


}
