<?php

namespace App\Listeners\Order;

use App\Repositories\Billing\RefundOrderBillingRepository;
use App\Repositories\Order\Refund\RefundOrderRepositoryContract;
use App\Services\Pay\Events\PingxxRefundPaymentIsSucceed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRefundSucceed {

    /**
     * @var RefundOrderBillingRepository
     */
    private $orderBillingRepo;

    /**
     * @var RefundOrderRepositoryContract
     */
    private $orderRepo;

    /**
     * Create the event listener.
     *
     * @param RefundOrderBillingRepository $orderBillingRepo
     * @param RefundOrderRepositoryContract $orderRepo
     */
    public function __construct(RefundOrderBillingRepository $orderBillingRepo, RefundOrderRepositoryContract $orderRepo)
    {
        $this->orderBillingRepo = $orderBillingRepo;
        $this->orderRepo = $orderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  PingxxRefundPaymentIsSucceed $event
     * @return void
     */
    public function handle(PingxxRefundPaymentIsSucceed $event)
    {
        $payment = $event->refund_payment;

        $billing = $this->orderBillingRepo->updateAsPaid($payment['billing_id']);

        $this->orderRepo->updateRefundAsRefunding($billing['order_id']);
    }

}
