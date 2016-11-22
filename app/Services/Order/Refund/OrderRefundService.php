<?php namespace App\Services\Order\Refund;

use App\Repositories\Billing\RefundOrderBillingRepository;
use App\Repositories\Order\RefundClientOrderRepository;
use App\Services\Billing\BillingProtocol;
use App\Services\Billing\RefundOrderBillingService;
use App\Services\Pay\Pingxx\PingxxRefundService;
use App\Services\Pay\RefundAbleContract;

class OrderRefundService implements OrderRefundServiceContract {

    /**
     * @var RefundClientOrderRepository
     */
    private $orderRepo;

    /**
     * @var RefundOrderBillingRepository
     */
    private $billingRepo;

    /**
     * @var RefundAbleContract
     */
    private $refund;

    /**
     * @var RefundOrderBillingService
     */
    private $billingService;

    /**
     * OrderRefundService constructor.
     * @param RefundClientOrderRepository $orderRepo
     * @param RefundOrderBillingRepository $billingRepo
     * @param PingxxRefundService $refund
     * @param RefundOrderBillingService $billingService
     */
    public function __construct(
        RefundClientOrderRepository $orderRepo,
        RefundOrderBillingRepository $billingRepo,
        PingxxRefundService $refund,
        RefundOrderBillingService $billingService
    )
    {
        $this->orderRepo = $orderRepo;
        $this->billingRepo = $billingRepo;
        $this->refund = $refund;
        $this->billingService = $billingService;
    }

    public function refund($refund_order)
    {
        $order = $this->orderRepo->getOrder($refund_order);

        $refer_order = $order->refer->first();
        $refer_order->load('billings');

        $pay_amount = $order['pay_amount'];
        $pingxx_refund_amount = $pay_amount;
        $refund_order_billing = null;

        //生成退款billings
        foreach ($refer_order['billings'] as $billing) {

            if ($billing['pay_type'] == BillingProtocol::BILLING_TYPE_OF_MONEY) {
                $pingxx_refund_amount = $pingxx_refund_amount > $billing['amount'] ? $billing['amount'] : $pingxx_refund_amount;
                $refund_order_billing = $this->billingRepo->setType(BillingProtocol::BILLING_TYPE_OF_MONEY)->createBilling($pingxx_refund_amount, [
                    'order_id' => $order['id'],
                    'refer_billing_id' => $billing['id']
                ]);
            }
        }

        if ($pingxx_refund_amount < $pay_amount) {
            #todo 积分、余额退款
        }

        if (!is_null($refund_order_billing)) {
            $this->refund->refund($this->billingService->setID($refund_order_billing));
        }

        return $this->orderRepo->updateRefundAsRefunding($order['order_no']);
    }
    
    

}
