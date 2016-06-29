<?php namespace App\Repositories\Preorder\Product;

use App\Models\Subscribe\Preorder;
use App\Models\Subscribe\PreorderSku;

class PreorderSkusRepository implements PreorderSkusRepositoryContract {

    public function getAll($order_id)
    {
        return PreorderSku::query()->where('preorder_id', $order_id)->get();
    }

    public function createPreorderProducts($order_id, $product_skus)
    {
        if ($order_id instanceof Preorder) {
            $order_id = $order_id['id'];
        }
        $order_skus = [];
        foreach ($product_skus as $key => $product_sku) {
            $product_sku = array_only($product_sku, ['product_sku_id', 'name', 'price', 'quantity', 'total_amount', 'weekday', 'daytime']);
            $product_sku['preorder_id'] = $order_id;
            $order_skus[] = PreorderSku::create($product_sku);
        }

        return $order_skus;
    }

    public function deletePreorderProducts($order_id)
    {
        return PreorderSku::query()->where('preorder_id', $order_id)->delete();
    }
}
