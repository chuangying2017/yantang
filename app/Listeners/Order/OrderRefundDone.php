<?php

namespace App\Listeners\Order;

use App\Repositories\Billing\RefundOrderBillingRepository;
use App\Repositories\Order\Refund\RefundOrderRepositoryContract;
use App\Services\Pay\Events\PingxxRefundPaymentIsDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRefundDone {

    /**
     * @var RefundOrderRepositoryContract
     */
    private $orderRepo;


    protected $orderBillingRepo;

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
     * @param  PingxxRefundPaymentIsDone $event
     * @return void
     */
    public function handle(PingxxRefundPaymentIsDone $event)
    {
        $refund_payment = $event->refund_payment;

        $billing = $this->orderBillingRepo->getBilling($refund_payment['billing_id']);
        //原账单退款金额更新
        $billing->refer()->update(['return_amount' => $billing['amount']]);

        $this->orderRepo->updateRefundAsDone($billing['order_id']);
    }


}
