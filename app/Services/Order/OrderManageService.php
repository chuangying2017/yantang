<?php namespace App\Services\Order;

use App\Events\Order\OrderIsCancel;
use App\Events\Order\OrderIsPaid;
use App\Repositories\Billing\OrderBillingRepository;
use App\Repositories\Order\ClientOrderRepository;
use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Order\Deliver\OrderDeliverRepository;
use App\Services\Billing\OrderBillingService;
use App\Services\Order\Exceptions\BillingNotPaidException;
use App\Services\Order\OrderProtocol;
use App\Services\Order\Refund\OrderRefundService;
use App\Services\Order\Refund\RefundOrderGenerator;

class OrderManageService implements OrderManageContract {

    /**
     * @var OrderBillingService
     */
    private $orderBillingService;
    /**
     * @var OrderBillingRepository
     */
    private $orderBillingRepo;
    /**
     * @var ClientOrderRepositoryContract
     */
    private $orderRepositoryContract;
    /**
     * @var OrderDeliverRepository
     */
    private $orderDeliverRepo;


    /**
     * OrderManageService constructor.
     * @param OrderBillingRepository $orderBillingRepo
     * @param OrderBillingService $orderBillingService
     */
    public function __construct(
        OrderBillingRepository $orderBillingRepo,
        OrderBillingService $orderBillingService,
        ClientOrderRepository $orderRepositoryContract,
        OrderDeliverRepository $orderDeliverRepo
    )
    {
        $this->orderBillingService = $orderBillingService;
        $this->orderBillingRepo = $orderBillingRepo;
        $this->orderRepositoryContract = $orderRepositoryContract;
        $this->orderDeliverRepo = $orderDeliverRepo;
    }

    public function orderCancel($order_id, $memo = '', $order_skus_info = null)
    {
        $order = $this->orderRepositoryContract->getOrder($order_id, false);
        
        //未支付,直接取消
        if ($order['pay_status'] == OrderProtocol::PAID_STATUS_OF_UNPAID) {
            return $this->orderRepositoryContract->updateOrderStatusAsCancel($order);
        }

        //生成退款退货订单
        $refund_order_generator = app()->make(RefundOrderGenerator::class);
        $refund_order = $refund_order_generator->refund($order_id, $order_skus_info, $memo);

        //未发货,直接退款
        if ($order['status'] == OrderProtocol::STATUS_OF_PAID) {
            //执行退款
            app()->make(OrderRefundService::class)->refund($refund_order);
            return $this->orderRepositoryContract->updateOrderStatusAsCancel($order);
        }

        return $order;
    }

    public function orderPaid($order_id)
    {
        try {
            $billings = $this->orderBillingRepo->getAllBilling($order_id);

            $order = $this->orderRepositoryContract->getOrder($order_id, false);

            //check Order status
            if ($order['pay_status'] == OrderProtocol::PAID_STATUS_OF_PAID) {
                event(new OrderIsPaid($order));
                return $order;
            }

            $pay_channel = '';
            foreach ($billings as $billing) {
                if (!$this->orderBillingService->setID($billing)->isPaid()) {
                    throw new BillingNotPaidException();
                }
                if ($this->orderBillingService->getPayType() == OrderProtocol::BILLING_TYPE_OF_MONEY) {
                    $pay_channel = $billing['pay_channel'];
                }
            }
            $order = $this->orderRepositoryContract->updateOrderStatusAsPaid($order, $pay_channel);

            return $order;
        } catch (BillingNotPaidException $e) {
            #TODO:  重新检查billings支付状态
            throw $e;
        }
    }

    public function orderDeliver($order_id, $company, $post_no)
    {
        $order = $this->orderRepositoryContract->getOrder($order_id, false);

        if (!OrderProtocol::statusIs(OrderProtocol::STATUS_OF_PAID, $order['status'])) {
            throw new \Exception('订单状态错误,当前状态:' . $order['status'] . ' ' . '不能改为发货');
        }

        $this->orderDeliverRepo->createOrderDeliver($order['id'], $company, $post_no);

        return $this->orderRepositoryContract->updateOrderStatusAsDeliver($order);
    }

    public function orderDone($order_id)
    {
        $order = $this->orderRepositoryContract->getOrder($order_id, false);

        if (!OrderProtocol::statusIs(OrderProtocol::REFUND_STATUS_OF_SHIPPING, $order['status'])) {
            throw new \Exception('订单状态错误,当前状态:' . $order['status'] . ' ' . '不能改为收货');
        }

        return $this->orderRepositoryContract->updateOrderStatusAsDone($order);
    }
}
