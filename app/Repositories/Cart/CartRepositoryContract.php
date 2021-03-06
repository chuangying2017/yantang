<?php namespace App\Repositories\Cart;

interface CartRepositoryContract {

    public function getCount();

    public function getAll($with_sku = true);

    public function getMany($cart_ids, $with_sku = true);

    public function addOne($product_sku_id, $quantity);

    public function addMany($cart_data);

    public function updateQuantity($product_sku_id, $quantity);

    public function deleteOne($cart_id);

    public function deleteAll();

    public function deleteMany($cart_ids);

}
