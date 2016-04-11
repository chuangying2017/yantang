<?php

namespace App\Services\Orders\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderCancel extends Event
{
    use SerializesModels;
    public $order;
    public $skus;
    public $express;
    public $billings;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order_info)
    {
        $this->order = $order_info;
        $this->skus = $order_info->skus;
        $this->billings = $order_info->billings;
        $this->express = $order_info->express;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
