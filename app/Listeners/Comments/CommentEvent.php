<?php

namespace App\Listeners\Comments;

use App\Models\Billing\OrderBilling;
use App\Models\Order\Order;
use App\Repositories\Comment\CommentRepositoryContract;
use App\Services\Billing\BillingProtocol;
use App\Services\Pay\Events\PingxxPaymentIsPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentEvent
{
    public $commentRepository;
    /**
     * Create the event listener.
     *
     * @param CommentRepositoryContract $commentRepositoryContract
     */
    public function __construct(CommentRepositoryContract $commentRepositoryContract)
    {

        $this->commentRepository = $commentRepositoryContract;

    }

    /**
     * Handle the event.
     *
     * @param  PingxxPaymentIsPaid  $event
     * @return void
     */
    public function handle(PingxxPaymentIsPaid $event)
    {

        $payment = $event->payment;

        if($payment['billing_type'] == BillingProtocol::BILLING_TYPE_OF_ORDER_BILLING){

            $this->commentRepository->create('0','0',$payment->billing->order_id,Order::class);

        }
    }

}
