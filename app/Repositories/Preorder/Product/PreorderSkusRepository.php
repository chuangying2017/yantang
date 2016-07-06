<?php namespace App\Repositories\Preorder\Product;

use App\Models\Subscribe\Preorder;
use App\Models\Subscribe\PreorderSku;

class PreorderSkusRepository implements PreorderSkusRepositoryContract {

    public function getAll($order_id)
    {
        return PreorderSku::query()->where('order_id', $order_id)->get();
    }

    public function createPreorderProducts($product_skus)
    {
        $order_skus = [];
        foreach ($product_skus as $key => $product_sku) {
            $product_sku = array_only($product_sku, [
                'order_id',
                'order_sku_id',
                'product_id',
                'product_sku_id',
                'total',
                'remain',
                'name',
                'cover_image',
                'price',
                'quantity',
                'total_amount'
            ]);
            $order_skus[] = PreorderSku::create($product_sku);
        }

        return $order_skus;
    }

    public function deletePreorderProducts($order_id)
    {
        return PreorderSku::query()->where('order_id', $order_id)->delete();
    }

    public function decrement($id, $quantity)
    {
        return PreorderSku::query()->find($id)->decrement('remain', $quantity);
    }
}
