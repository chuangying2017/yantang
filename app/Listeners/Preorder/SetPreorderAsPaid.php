<?php

namespace App\Listeners\Preorder;

use App\Events\Order\OrderIsPaid;
use App\Events\Preorder\PreorderIsPaid;
use App\Models\Subscribe\Preorder;
use App\Repositories\Comment\CommentRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Notify\NotifyProtocol;
use App\Services\Order\OrderProtocol;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SetPreorderAsPaid {

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    private $commentRepo;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderRepositoryContract $preorderRepo, CommentRepositoryContract $commentRepositoryContract)
    {
        $this->preorderRepo = $preorderRepo;

        $this->commentRepo = $commentRepositoryContract;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        $order = $event->order;
        \Log::debug('test: SetPreorderAsPaid', [$event]);

        if ($order['order_type'] == OrderProtocol::ORDER_TYPE_OF_SUBSCRIBE) {

            $preorder = $this->preorderRepo->updatePreorderStatusByOrder($order['id'], PreorderProtocol::ORDER_STATUS_OF_ASSIGNING);

            $this->commentRepo->create('0','0',$preorder['id'],Preorder::class);

            event(new PreorderIsPaid($preorder));
        }
    }
}
