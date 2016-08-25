<?php namespace App\Repositories\Order\Sku;
interface OrderSkuRepositoryContract {

    public function createOrderSkus($order, $data);

    public function getOrderSkus($order_id);

    public function getOrderSkusByIds($order_sku_ids);
}
