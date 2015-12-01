<?php

namespace App\Services\Orders\Event;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderConfirm extends Event {

    use SerializesModels;

    public $order_id;
    public $products;
    public $tickets;
    public $address;
    public $user_id;
    public $carts;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order_id, $order_info)
    {
        $this->order_id = $order_id;
        $this->user_id = $order_info['user_id'];
        $this->products = $order_info['products'];
        #todo  添加多种优惠
        $this->tickets = $order_info['marketing']['coupons'];
        $this->address = $order_info['address'];
        $this->carts = isset($order_info['carts']) ? $order_info['carts'] : null;
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
