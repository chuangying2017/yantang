<?php

namespace App\Listeners\Collect;

use App\Events\Order\OrderIsPaid;
use App\Models\Collect\CollectOrder;
use App\Repositories\Order\CollectClientOrderRepository;
use Carbon\Carbon;

class SetCollectOrderAsPaid {
    /**
     * Handle the event.
     *
     * @param  SetColletcorderAsPaid $event
     * @return void
     */
    public function handle(OrderIsPaid $event)
    {
        \Log::debug('test: SetColletcorderAsPaid', [$event]);

        $order = $event->order;

        $colletcOrder = CollectOrder::where('order_id', $order['id'])->first();
        if ($colletcOrder) {
            $colletcOrder->update(['pay_at'=> Carbon::now()]);
        }
    }
}
