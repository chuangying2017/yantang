<?php namespace App\Repositories\Product;

interface ProductSubscribeRepositoryContract {

    public function getAllSubscribedProducts($status = ProductProtocol::VAR_PRODUCT_STATUS_UP, $with_time = true);

    public function setProductsStopSubscribe($product_id);

    public function setProductsStartSubscribe($product_id);

}
