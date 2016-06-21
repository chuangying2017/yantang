<?php namespace App\Repositories\Product\Sku;

use App\Models\Product\ProductSku;

class EloquentProductSkuRepository implements ProductSkuRepositoryContract, ProductSkuStockRepositoryContract {

    public function createSku($sku_data, $product_id)
    {
        return $this->updateOrCreate($sku_data, null, $product_id);
    }

    public function updateSku($product_sku_id, $sku_data)
    {
        return $this->updateOrCreate($sku_data, $product_sku_id);
    }

    protected function updateOrCreate($sku_data, $product_sku_id = null, $product_id = 0)
    {
        if (!is_null($product_sku_id)) {
            $sku = ProductSku::findOrFail($product_sku_id);
        } else {
            $sku = new ProductSku([
                'product_id' => $product_id,
                'sku_no' => uniqid('psn_'),
            ]);
        }

        $sku->fill(
            [
                'name' => $sku_data['name'],
                'cover_image' => $sku_data['cover_image'],
                'display_price' => $sku_data['display_price'],
                'express_fee' => $sku_data['express_fee'],
                'price' => $sku_data['price'],
                'income_price' => $sku_data['income_price'],
                'settle_price' => $sku_data['settle_price'],
                'subscribe_price' => array_get($sku_data, 'subscribe_price', $sku_data['price']),
                'service_fee' => array_get($sku_data, 'service_fee', 0),
                'bar_code' => $sku_data['bar_code'],
                'stock' => $sku_data['stock'],
                'attr' => array_get($sku_data, 'attr', ''),
            ]);
        $sku->save();

        if (count(array_get($sku_data, 'sku_ids', [])) > 0) {
            $sku->mix()->sync($sku_data['sku_ids']);
        }

        if (count(array_get($sku_data, 'attr_value_ids', [])) > 0) {
            $sku->attributeValues()->sync($sku_data['attr_value_ids']);
        }

        return $sku;

    }

    public function deleteSku($product_sku_id)
    {
        $sku = ProductSku::findOrFail($product_sku_id);
        $this->detachAllRelation($sku);
        $sku->delete();
        return 1;
    }

    public function deleteSkusOfProduct($product_id)
    {
        $skus = ProductSku::where('product_id', $product_id)->get();
        $count = count($skus);
        if ($count > 0) {
            foreach ($skus as $sku) {
                $this->detachAllRelation($sku);
                $sku->delete();
            }
        }

        return $count;
    }

    public function updateSkusOfProduct($product_id, $new_sku_data)
    {
        $current_skus = [];
        $remain_sku_ids = [];
        $old_sku_ids = ProductSku::where('product_id', $product_id)->lists('id')->all();
        foreach ($new_sku_data as $sku_data) {
            if (array_key_exists('id', $sku_data)) {
                $current_skus[] = $this->updateSku($sku_data['id'], $sku_data);
                $remain_sku_ids[] = $sku_data['id'];
            } else {
                $current_skus[] = $this->createSku($sku_data, $product_id);
            }
        }

        $detach_sku_ids = array_diff($old_sku_ids, $remain_sku_ids);
        foreach ($detach_sku_ids as $detach_sku_id) {
            $this->deleteSku($detach_sku_id);
        }

        return $current_skus;
    }

    protected function detachAllRelation($sku)
    {
        $sku->mix()->detach();
        $sku->attributeValues()->detach();
    }

    public function getSkus($sku_ids)
    {
        return ProductSku::find($sku_ids);
    }

    public function increaseStock($product_sku_id, $quantity = 1)
    {
        $sku = $this->getSkus($product_sku_id);
        $sku->stock = $sku->stock + $quantity;
        $sku->save();

        return $sku;
    }

    public function decreaseStock($product_sku_id, $quantity = 1)
    {
        $sku = $this->getSkus($product_sku_id);
        if ($sku->stock < $quantity) {
            throw new \Exception('库存不足,减库存失败');
        }
        $sku->stock = $sku->stock - $quantity;
        $sku->sales = $sku->sales + $quantity;
        $sku->save();

        return $sku;
    }

    public function getStock($product_sku_id)
    {
        $sku = $this->getSkus($product_sku_id);
        return $sku->stock;
    }

    public function enoughStock($product_sku_id, $quantity)
    {
        return $this->getStock($product_sku_id) > $quantity;
    }
}
