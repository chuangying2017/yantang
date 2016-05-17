<?php namespace App\Repositories\Product\Sku;

use App\Models\Product\ProductSku;

class EloquentProductSkuRepository implements ProductSkuRepositoryContract {

    public function createSku($sku_data, $product_id)
    {
        $sku_model = ProductSku::create(
            [
                'sku' => uniqid('psn_'),
                'product_id' => $product_id ?: 0,
                'name' => $sku_data['name'],
                'cover_image' => $sku_data['cover_image'],
                'display_price' => $sku_data['display_price'],
                'express_fee' => $sku_data['express_fee'],
                'price' => $sku_data['price'],
                'income_price' => $sku_data['income_price'],
                'settle_price' => $sku_data['settle_price'],
                'bar_code' => $sku_data['bar_code'],
                'stock' => $sku_data['stock'],
                'attr' => $sku_data['attr'],
            ]);

        if (count(array_get($sku_data, 'sku_ids', [])) > 0) {
            $sku_model->mix()->attach($sku_data['sku_ids']);
        }

        if (count(array_get($sku_data, 'attr_value_ids', [])) > 0) {
            $sku_model->attributeValues()->attach($sku_data['attr_value_ids']);
        }

        return $sku_model;
    }

    public function updateSku($product_sku_id, $sku_data)
    {
        $sku_model = ProductSku::findOrFail($product_sku_id);

        $sku_model->save(
            [
                'sku' => uniqid('psn_'),
                'name' => $sku_data['name'],
                'cover_image' => $sku_data['cover_image'],
                'display_price' => $sku_data['display_price'],
                'express_fee' => $sku_data['express_fee'],
                'price' => $sku_data['price'],
                'income_price' => $sku_data['income_price'],
                'settle_price' => $sku_data['settle_price'],
                'bar_code' => $sku_data['bar_code'],
                'stock' => $sku_data['stock'],
                'attr' => $sku_data['attr'],
            ]);

        if (count(array_get($sku_data, 'sku_ids', [])) > 0) {
            $sku_model->mix()->attach($sku_data['sku_ids']);
        }

        if (count(array_get($sku_data, 'attr_value_ids', [])) > 0) {
            $sku_model->attributeValues()->attach($sku_data['attr_value_ids']);
        }

        return $sku_model;
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
        $remain_sku_ids = [];
        $old_sku_ids = ProductSku::where('product_id', $product_id)->lists('id')->all();
        foreach ($new_sku_data as $sku_data) {
            if (array_key_exists('id', $sku_data)) {
                $this->updateSku($sku_data['id'], $sku_data);
                $remain_sku_ids[] = $sku_data['id'];
            } else {
                $this->createSku($sku_data, $product_id);
            }
        }

        $detach_sku_ids = array_diff($old_sku_ids, $remain_sku_ids);
        foreach ($detach_sku_ids as $detach_sku_id) {
            $this->deleteSku($detach_sku_ids);
        }

        return 1;
    }

    protected function detachAllRelation($sku)
    {
        $sku->mix()->detach();
        $sku->attributeValues()->detach();
    }

}
