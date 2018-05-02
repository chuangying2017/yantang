<?php

namespace App\Listeners\Product;

use App\Events\Order\OrderIsCancel;
use App\Repositories\Order\Sku\OrderSkuRepositoryContract;
use App\Repositories\Product\Sku\ProductSkuStockRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseProductSkuStock {

    /**
     * @var OrderSkuRepositoryContract
     */
    private $orderSkuRepo;
    /**
     * @var ProductSkuStockRepositoryContract
     */
    private $stockRepo;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderSkuRepositoryContract $orderSkuRepo, ProductSkuStockRepositoryContract $stockRepo)
    {
        $this->orderSkuRepo = $orderSkuRepo;
        $this->stockRepo = $stockRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderIsCancel $event
     * @return void
     */
    public function handle(OrderIsCancel $event)
    {
        $order = $event->order;
        
        $order_skus = $this->orderSkuRepo->getOrderSkus($order['id']);
        foreach ($order_skus as $order_sku) {
            $this->stockRepo->increaseStock($order_sku['product_sku_id'], $order_sku['quantity']);
        }
    }
}
