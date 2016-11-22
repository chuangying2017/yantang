<?php namespace App\Services\Order\Checkout;

use App\Repositories\Billing\OrderBillingRepository;
use App\Services\Billing\OrderBillingService;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxPayService;
use App\Services\Pay\ThirdPartyPayContract;

class OrderCheckoutService implements OrderCheckoutContract {

    /**
     * @var OrderBillingRepository
     */
    private $billingRepo;
    /**
     * @var PingxxPayService
     */
    private $payService;
    /**
     * @var OrderBillingService
     */
    private $orderBillingService;


    /**
     * OrderCheckoutService constructor.
     * @param OrderBillingRepository $billingRepo
     * @param PingxxPayService $payService
     * @param OrderBillingService $orderBillingService
     */
    public function __construct(OrderBillingRepository $billingRepo, PingxxPayService $payService, OrderBillingService $orderBillingService)
    {
        $this->billingRepo = $billingRepo;
        $this->payService = $payService;
        $this->orderBillingService = $orderBillingService;
    }

    public function checkout($order_id, $pay_type = OrderProtocol::BILLING_TYPE_OF_MONEY, $pay_channel = null)
    {
        if ($pay_type == OrderProtocol::BILLING_TYPE_OF_MONEY) {
            return $this->payWithPingxx($order_id, $pay_channel);
        }

        #todo 其他支付方式
        return false;
    }

    protected function payWithPingxx($order_id, $pay_channel)
    {
        $billing = $this->billingRepo->getBillingOfType($order_id, OrderProtocol::BILLING_TYPE_OF_MONEY);

        if (!$billing) {
            throw new \Exception('订单无支付信息');
        }

        if ($billing['amount'] <= 0) {
            $this->billingRepo->updateAsPaid($billing);
            return OrderProtocol::ORDER_IS_PAID;
        }

        $charge = $this->payService->setChannel($pay_channel)->pay($this->orderBillingService->setID($billing));

        return $charge;
    }

    public function billingPaid($payment_no)
    {
        $payment = $this->payService->checkPaymentPaid($payment_no);
        if (!$payment) {
            return false;
        }

        $billing = $this->billingRepo->getBilling($payment['billing_id']);

        if ($this->orderBillingService->setID($billing)->isPaid()) {
            return $billing;
        }

        return $this->billingRepo->updateAsPaid($billing, $payment['channel']);
    }


    public function checkOrderIsPaid($order_id)
    {
        $billing = $this->billingRepo->getBillingOfType($order_id, OrderProtocol::BILLING_TYPE_OF_MONEY);

        if (!$billing) {
            return false;
        }

        return $this->orderBillingService->setID($billing)->isPaid(true);
    }
}
