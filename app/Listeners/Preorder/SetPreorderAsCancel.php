<?php

namespace App\Listeners\Preorder;

use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Pay\Events\PingxxRefundPaymentIsSucceed;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsCancel {

    /**
     * Create the event listener.
     *
     * @param PreorderRepositoryContract $preorderRepo
     */
    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  PingxxRefundPaymentIsSucceed $event
     * @return void
     */
    public function handle(PingxxRefundPaymentIsSucceed $event)
    {
        $refund_payment = $event->refund_payment;
        $refer_order_billing =  $refund_payment->payment->billing;
        $this->preorderRepo->updatePreorderStatusByOrder($refer_order_billing['order_id'], PreorderProtocol::ORDER_STATUS_OF_CANCEL);
    }

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;
}
