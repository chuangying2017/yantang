<?php namespace App\Services\Cart;

use App\Models\Cart;

class CartRepository {


    public static function create($user_id, $product_sku_id, $quantity = 1)
    {
        return Cart::updateOrCreate(
            compact('user_id', 'product_sku_id'),
            compact('user_id', 'product_sku_id', 'quantity')
        );
    }

    public static function get($cart_id)
    {
        if ($cart_id instanceof Cart) {
            return $cart_id;
        }
        if (is_array($cart_id)) {
            return Cart::whereIn('id', $cart_id)->get();
        }

        return Cart::findOrFail($cart_id);
    }

    protected static function query($user_id, $product_sku_id = null)
    {
        $query = Cart::where('user_id', $user_id);
        if ( ! is_null($product_sku_id)) {
            $query = $query->where('product_sku_id', $product_sku_id);
        }

        return $query->get();
    }


    public static function update($cart_id, $quantity)
    {
        return Cart::where('id', $cart_id)->update(['quantity' => $quantity]);
    }

    public static function increment($cart_id, $quantity)
    {
        return Cart::where('id', $cart_id)->increment('quantity', $quantity);
    }

    public static function decrement($cart_id, $quantity)
    {
        return Cart::where('id', $cart_id)->decrement('quantity', $quantity);
    }

    public static function remove($cart_id)
    {
        $carts = to_array($cart_id);

        return Cart::whereIn('id', $carts)->delete();
    }

    public static function all($user_id)
    {
        return self::query($user_id);
    }

    public static function exist($user_id, $product_sku_id)
    {
        return Cart::where(compact('user_id', 'product_sku_id'))->first();
    }

}
