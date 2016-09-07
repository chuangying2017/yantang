<?php namespace App\Services\Order;

use App\Events\Order\OrderIsPaid;
use App\Repositories\Billing\OrderBillingRepository;
use App\Repositories\Order\ClientOrderRepository;
use App\Repositories\Order\ClientOrderRepositoryContract;
use App\Repositories\Order\Deliver\OrderDeliverRepository;
use App\Services\Billing\OrderBillingService;
use App\Services\Order\Checkout\OrderCheckoutService;
use App\Services\Order\Exceptions\BillingNotPaidException;
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
    private $orderRepo;
    /**
     * @var OrderDeliverRepository
     */
    private $orderDeliverRepo;


    /**
     * OrderManageService constructor.
     * @param OrderBillingRepository $orderBillingRepo
     * @param OrderBillingService $orderBillingService
     * @param ClientOrderRepository $orderRepositoryContract
     * @param OrderDeliverRepository $orderDeliverRepo
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
        $this->orderRepo = $orderRepositoryContract;
        $this->orderDeliverRepo = $orderDeliverRepo;
    }

    public function orderCancel($order_id, $memo = '', $order_skus_info = null)
    {
        $order = $this->orderRepo->getOrder($order_id, false);

        if (!($order['refund_status'] == OrderProtocol::REFUND_STATUS_OF_DEFAULT || $order['refund_status'] == OrderProtocol::REFUND_STATUS_OF_REJECT)) {
            throw new \Exception('订单退款处理中,无法重复提交');
        }

        //未支付,直接取消
        if ($order['pay_status'] == OrderProtocol::PAID_STATUS_OF_UNPAID) {
            if (!app()->make(OrderCheckoutService::class)->checkOrderIsPaid($order['id'])) {
                return $this->orderRepo->updateOrderStatusAsCancel($order);
            }
        }

        //生成退款退货订单
        $refund_order_generator = app()->make(RefundOrderGenerator::class);
        $refund_order = $refund_order_generator->refund($order_id, $order_skus_info, $memo);
        
        //未发货,直接退款
        $order = $this->orderRepo->getOrder($order['id'], false);
        if ($order['status'] == OrderProtocol::STATUS_OF_PAID) {
            //执行退款
            app()->make(OrderRefundService::class)->refund($refund_order);
            return $this->orderRepo->updateOrderStatusAsCancel($order);
        }

        return $order;
    }

    public function orderPaid($order_id)
    {
        try {
            $billings = $this->orderBillingRepo->getAllBilling($order_id);

            $order = $this->orderRepo->getOrder($order_id, false);

            //check Order status
            if ($order['pay_status'] == OrderProtocol::PAID_STATUS_OF_PAID) {
                event(new OrderIsPaid($order));
                return $order;
            }

            $pay_channel = '';
            foreach ($billings as $billing) {
                if (!$this->orderBillingService->setID($billing)->isPaid(true)) {
                    throw new BillingNotPaidException();
                }
                if ($this->orderBillingService->getPayType() == OrderProtocol::BILLING_TYPE_OF_MONEY) {
                    $pay_channel = $billing['pay_channel'];
                }
            }
            $order = $this->orderRepo->updateOrderStatusAsPaid($order, $pay_channel);

            return $order;
        } catch (BillingNotPaidException $e) {
            throw $e;
        }
    }

    public function orderDeliver($order_id, $company, $post_no)
    {
        $order = $this->orderRepo->getOrder($order_id, false);

        if (!OrderProtocol::statusIs(OrderProtocol::STATUS_OF_PAID, $order['status'])) {
            throw new \Exception('订单状态错误,当前状态:' . $order['status'] . ' ' . '不能改为发货');
        }

        $this->orderDeliverRepo->createOrderDeliver($order['id'], $company, $post_no);

        return $this->orderRepo->updateOrderStatusAsDeliver($order);
    }

    public function orderDone($order_id)
    {
        $order = $this->orderRepo->getOrder($order_id, false);

        if (!OrderProtocol::statusIs(OrderProtocol::REFUND_STATUS_OF_SHIPPING, $order['status'])) {
            throw new \Exception('订单状态错误,当前状态:' . $order['status'] . ' ' . '不能改为收货');
        }

        return $this->orderRepo->updateOrderStatusAsDone($order);
    }


    public function orderIsFirstPaid($order)
    {
        $order = $this->orderRepo->getOrder($order, false);
        $recent_order = $this->orderRepo->getFirstPaidOrder($order->user_id, $order->order_type);
        return $order['id'] === $recent_order['id'];
    }
}
