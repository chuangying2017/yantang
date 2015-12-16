<?php namespace App\Services\Cart;

use App\Services\Orders\Helpers\ProductsHelper;

class CartService {

    const HAS_FIND_PRODUCT_INFO = 1;

    use ProductsHelper;

    /**
     * 添加商品到购物车,检查是否有效,如果已存在则增加数量,否则新建,返回购物车对象
     * @param $user_id
     * @param $product_sku_id
     * @param $quantity
     * @return static
     * @throws \Exception
     */
    public static function add($user_id, $product_sku_id, $quantity)
    {
        $total_quantity = $quantity;
        $cart = CartRepository::exist($user_id, $product_sku_id);


        if ($cart) {
            $total_quantity += $cart['quantity'];
        }

        if ($err_msg = self::checkProductCanNotAfford($product_sku_id, $total_quantity)) {
            throw new \Exception($err_msg);
        }

        if ( ! $cart) {
            $cart = CartRepository::create($user_id, $product_sku_id, $total_quantity);
        } else {
            CartRepository::increment($cart->id, $quantity);
        }

        return $cart;
    }

    /**
     * 更新购物车,如果数量为0则删除购物车,高于原本数量则需要检查商品是否能添加,返回购物车对象
     * @param $cart_id
     * @param int $quantity
     * @return mixed
     * @throws \Exception
     */
    public static function update($cart_id, $quantity = 1)
    {
        try {

            if ($quantity <= 0) {
                return self::remove($cart_id);
            }

            $cart = CartRepository::get($cart_id);

            if ($cart->quantity > $quantity) {
                CartRepository::update($cart->id, $quantity);
            } else {
                if ($err_msg = self::checkProductCanNotAfford($cart->product_sku_id, $quantity)) {
                    throw new \Exception($err_msg);
                }

                CartRepository::update($cart->id, $quantity);
            }
            $cart->quantity = $quantity;

            return $cart;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public static function remove($cart_id)
    {
        return CartRepository::remove($cart_id);
    }

    public static function all($user_id)
    {
        return CartRepository::all($user_id);
    }


    public static function take($cart_id)
    {
        $carts = CartRepository::get($cart_id);

        return $carts->toArray();
    }


    public static function lists($user_id)
    {
        $cart_info = self::all($user_id);
        $products_info = self::getProductSkuInfo($cart_info);
        $skus_info = $products_info['data'];
        foreach ($cart_info as $cart_key => $cart) {
            $cart->product_sku = $skus_info[ $cart['product_sku_id'] ];
            $cart_info->$cart_key = $cart;
        }

        return $cart_info;
    }

}
