<?php namespace App\Services\Cart;

use App\Models\Cart;

class CartRepository {

    /**
     * 购物车新增时
     * @param $user_id
     * @param $product_sku_id
     * @param $quantity
     * @return static
     */
    public static function add($user_id, $product_sku_id, $quantity)
    {
        $cart_item = Cart::where(compact('user_id', 'product_sku_id'))->first();

        return $cart_item
            ? $cart_item->increment('quantity', $quantity)
            : self::create($user_id, $product_sku_id, $quantity);
    }

    public static function get($cart_id)
    {
        if (is_array($cart_id)) {
            return Cart::whereIn('id', $cart_id)->get();
        }

        return Cart::find($cart_id);
    }

    public static function query($user_id, $product_sku_id = null)
    {
        $query = Cart::where('user_id', $user_id);
        if ( ! is_null($product_sku_id)) {
            $query = $query->where('product_sku_id', $product_sku_id);
        }

        return $query->get();
    }

    public static function create($user_id, $product_sku_id, $quantity)
    {
        return Cart::create(
            compact('user_id', 'product_sku_id', 'quantity')
        );
    }

    public static function update($cart_id, $quantity, $action = null)
    {
        if (is_null($action)) {
            return Cart::where('id', $cart_id)->update(['quantity' => $quantity]);
        } else if ($action == 'increment') {
            return Cart::where('id', $cart_id)->increment(['quantity' => $quantity]);
        } else if ($action == 'decrement') {
            return Cart::where('id', $cart_id)->decrement(['quantity' => $quantity]);
        }

        return 0;
    }

    public static function increment($cart_id, $quantity)
    {
        self::update($cart_id, $quantity, 'increment');
    }

    public static function decrement($cart_id, $quantity)
    {
        self::update($cart_id, $quantity, 'decrement');
    }

    public static function remove($cart_id)
    {
        $carts = to_array($cart_id);

        return Cart::whereIn('id', $carts)->delete();
    }

    public static function all($user_id)
    {
        return Cart::where('user_id', $user_id)->get();
    }

    public static function lists($user_id)
    {
        #todo 查询购物车商品详情
    }

}
