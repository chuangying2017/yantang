<?php namespace App\Repositories\Preorder\Product;

use App\Models\Subscribe\PreorderSku;

class PreorderSkusRepository implements PreorderSkusRepositoryContract {

    public function getAll($order_id)
    {
        return PreorderSku::query()->where('preorder_id', $order_id)->get();
    }

    public function createPreorderProducts($order_id, $product_skus)
    {
        foreach ($product_skus as $key => $product_sku) {
            $product_skus[$key] = array_only($product_sku, ['product_sku_id', 'name', 'price', 'quantity', 'total_amount']);
            $product_skus[$key]['preorder_id'] = $order_id;
        }
        return PreorderSku::insert($product_skus);
    }

    public function deletePreorderProducts($order_id)
    {
        return PreorderSku::query()->where('preorder_id', $order_id)->delete();
    }
}
