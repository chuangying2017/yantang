<?php namespace App\Services\Cart;

class CartService {

    public static function add($user_id, $product_sku_id, $quantity)
    {
        return CartRepository::add($user_id, $product_sku_id, $quantity);
    }

    public static function update($cart_id, $quantity)
    {
        return CartRepository::update($cart_id, $quantity);
    }

    public static function remove($cart_id)
    {
        return CartRepository::remove($cart_id);
    }

    public static function all($user_id)
    {
        return CartRepository::all($user_id);
    }

    public static function get($cart_id)
    {
        return CartRepository::get($cart_id);
    }

    public static function take($cart_id)
    {
        return CartRepository::get($cart_id);
    }


}
