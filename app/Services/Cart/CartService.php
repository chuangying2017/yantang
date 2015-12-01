<?php namespace App\Services\Cart;

use App\Services\Orders\ProductsHelper;

class CartService {

    const HAS_FIND_PRODUCT_INFO = 1;

    use ProductsHelper;

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



    public static function lists($user_id)
    {
        $cart_info = self::all($user_id);
        $products_info = self::getProductSkuInfo($cart_info);

        foreach ($cart_info as $cart_key => $cart) {

            $flag = 0;
            foreach ($products_info as $product_key => $product_info) {
                if ($cart['product_sku_id'] == $product_info['product_sku_id']) {
                    $flag = self::HAS_FIND_PRODUCT_INFO;
                    $cart_info[ $cart_key ] = array_merge($cart, $product_info['data']);

                    if (self::productCanAfford($product_info)) {
                        $cart_info[ $cart_key ]['can_buy'] = true;
                    } else {
                        $cart_info[ $cart_key ]['can_buy'] = false;
                        $cart_info[ $cart_key ]['err_msg'] = $product_info['err_msg'];
                    }

                    unset($products_info[ $product_key ]);
                    break;
                }
            }

            if ($flag != self::HAS_FIND_PRODUCT_INFO) {
                #todo 失效
            }

        }

        return $cart_info;
    }

}
