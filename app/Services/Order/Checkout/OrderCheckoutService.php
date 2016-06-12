<?php namespace App\Services\Order\Checkout;

use App\Repositories\Billing\OrderBillingRepository;
use App\Services\Billing\OrderBillingService;
use App\Services\Order\OrderProtocol;
use App\Services\Pay\Pingxx\PingxxPayService;

class OrderCheckoutService implements OrderCheckoutContract {

    /**
     * @var OrderBillingRepository
     */
    private $billingRepo;
    /**
     * @var PingxxPayService
     */
    private $pingxxPayService;
    /**
     * @var OrderBillingService
     */
    private $orderBillingService;


    /**
     * OrderCheckoutService constructor.
     * @param OrderBillingRepository $billingRepo
     * @param PingxxPayService $pingxxPayService
     * @param OrderBillingService $orderBillingService
     */
    public function __construct(OrderBillingRepository $billingRepo, PingxxPayService $pingxxPayService, OrderBillingService $orderBillingService)
    {
        $this->billingRepo = $billingRepo;
        $this->pingxxPayService = $pingxxPayService;
        $this->orderBillingService = $orderBillingService;
    }

    public function checkout($order_id, $pay_type, $pay_channel = null)
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

        $charge = $this->pingxxPayService->setChannel($pay_channel)->pay($this->orderBillingService->setID($billing));

        return $charge;
    }

    public function billingPaid($payment_no)
    {
        $payment = $this->pingxxPayService->checkPaymentPaid($payment_no);
        if (!$payment) {
            return false;
        }

        $billing = $this->billingRepo->getBilling($payment['payment_no']);

        if ($this->orderBillingService->setID($billing)->isPaid()) {
            return $billing;
        }

        return $this->billingRepo->updateAsPaid($billing, $payment['channel']);
    }


}
