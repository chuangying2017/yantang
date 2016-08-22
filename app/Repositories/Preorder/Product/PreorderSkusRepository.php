<?php namespace App\Repositories\Preorder\Product;

use App\Models\Subscribe\Preorder;
use App\Models\Subscribe\PreorderSku;
use Carbon\Carbon;

class PreorderSkusRepository implements PreorderSkusRepositoryContract {

    public function getAll($order_id)
    {
        return PreorderSku::query()->where('order_id', $order_id)->get();
    }

    public function createPreorderProducts($order_id, $product_skus)
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
            $product_sku['preorder_id'] = $order_id;

//            $temp_campaign_date = env('TEMP_CAMPAIGN_DATE', '2016-07-27');
//            if (Carbon::today() >= $temp_campaign_date && Carbon::today() <= Carbon::createFromFormat('Y-m-d', $temp_campaign_date)->addDays(15)) {
//                if ($product_sku['total'] == 15) {
//                    $product_sku['total'] += 3;
//                    $product_sku['remain'] += 3;
//                }
//            }

            $product_sku['per_day'] = $product_sku['quantity'];
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
        $sku = PreorderSku::query()->find($id);
        return $sku->decrement('remain', $quantity);
    }
}
