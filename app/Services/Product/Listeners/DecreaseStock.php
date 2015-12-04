<?php

namespace App\Services\Product\Listeners;

use App\Services\Orders\Event\OrderConfirm;
use App\Services\Product\ProductSkuService;

/**
 * Created by PhpStorm.
 * User: Bryant
 * Date: 3/12/2015
 * Time: 4:42 PM
 */
class DecreaseStock
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //todo@bryant 商品扣件库存的Listener
    }

    /**
     * Handle the event.
     *
     * @param  OrderConfirm $event
     * @return void
     */
    public function handle(OrderConfirm $event)
    {
        if (!is_null($event->products)) {
            foreach ($event->products as $product) {
                ProductSkuService::stockDown($product['product_sku_id'], $product['quantity']);
            }
        }
    }
}
