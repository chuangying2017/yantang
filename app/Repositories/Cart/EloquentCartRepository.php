<?php namespace App\Repositories\Cart;

use App\Models\Client\Cart;
use App\Repositories\Product\Sku\ProductSkuStockRepositoryContract;

class EloquentCartRepository implements CartRepositoryContract {

    /**
     * @var ProductSkuStockRepositoryContract
     */
    private $skuStockRepo;

    /**
     * EloquentCartRepository constructor.
     * @param ProductSkuStockRepositoryContract $skuStockRepo
     */
    public function __construct(ProductSkuStockRepositoryContract $skuStockRepo)
    {
        $this->skuStockRepo = $skuStockRepo;
    }

    public function getCount()
    {
        return Cart::count();
    }

    public function getAll($with_sku = true)
    {
        if ($with_sku) {
            return Cart::with('sku')->get();
        }
        return Cart::query()->get();
    }

    public function getMany($cart_ids, $with_sku = true)
    {
        if (!$with_sku) {
            return Cart::query()->find($cart_ids);
        }
        return Cart::with('sku')->find($cart_ids);
    }

    public function addOne($product_sku_id, $quantity)
    {
        $cart = Cart::with('sku')->where('product_sku_id', $product_sku_id)->first();
        if ($cart) {
            $cart->quantity = $cart->quantity + $quantity;
        } else {
            $cart = new Cart([
                'product_sku_id' => $product_sku_id,
                'quantity' => $quantity,
                'user_id' => access()->id()
            ]);
        }

        if ($this->skuStockRepo->enoughStock($product_sku_id, $quantity)) {
            $cart->save();
            return $cart;
        }

        return false;
    }

    public function addMany($cart_data)
    {
        $all_success = true;
        foreach ($cart_data as $cart_data_value) {
            $cart = $this->addOne($cart_data_value['product_sku_id'], $cart_data_value['quantity']);
            if (!$cart) {
                $all_success = false;
            }
        }

        return $all_success;
    }

    public function updateQuantity($cart_id, $quantity)
    {
        $cart = Cart::query()->find($cart_id);
        $cart->quantity += $quantity;
        $cart->save();
        return $cart;
    }

    public function deleteOne($cart_id)
    {
        return Cart::destroy($cart_id);
    }

    public function deleteAll()
    {
        return Cart::query()->delete();
    }

    public function deleteMany($cart_ids)
    {
        return Cart::destroy($cart_ids);
    }


}
