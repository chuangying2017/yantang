<?php

namespace App\Services\Product\Listeners;

use App\Models\OrderProduct;
use App\Services\Orders\Event\OrderCancel;
use App\Services\Product\ProductSkuService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseStock {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderCancel $event
     * @return void
     */
    public function handle(OrderCancel $event)
    {
        $skus = $event->skus;
        if (count($skus)) {
            $order_products_id = [];
            foreach ($skus as $sku) {
                $order_products_id[] = $sku['order_product_id'];
                ProductSkuService::stockUp($sku['id'], $sku['quantity']);
            }

            OrderProduct::whereIn('id', $order_products_id)->delete();
        }
    }
}
