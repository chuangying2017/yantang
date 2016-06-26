<?php namespace App\Repositories\Order\Sku;
interface OrderSkuRepositoryContract {

    public function createOrderSkus($order, $data);

    public function getOrderSkus($order_id);

}
