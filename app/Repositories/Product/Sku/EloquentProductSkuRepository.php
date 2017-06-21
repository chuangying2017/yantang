<?php namespace App\Repositories\Product\Sku;

use App\Models\Product\Product;
use App\Models\Product\ProductMeta;
use App\Models\Product\ProductSku;
use App\Repositories\Category\CategoryProtocol;
use App\Repositories\Product\ProductProtocol;
use App\Repositories\Product\Sku\SubscribeSkuRepositoryContract;

class EloquentProductSkuRepository implements ProductSkuRepositoryContract, ProductSkuStockRepositoryContract, ProductMixRepositoryContract, SubscribeSkuRepositoryContract
{

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
                'unit' => $sku_data['unit'],
                'attr' => array_get($sku_data, 'attr', ''),
                'type' => array_get($sku_data, 'type', ProductProtocol::TYPE_OF_ENTITY),
            ]);

        if ($this->isMixProductSku($sku_data)) {
            $sku->type = ProductProtocol::TYPE_OF_MIX;
        }

        $sku->save();

        if ($this->isMixProductSku($sku_data)) {
            $this->attachMixSku($sku_data, $sku);
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
        return ProductSku::query()->findOrFail($sku_ids);
    }

    public function getSkusWithTrash($sku_ids)
    {
        return ProductSku::query()->withTrashed()->findOrFail($sku_ids);
    }

    public function increaseStock($product_sku_id, $quantity = 1)
    {
        $sku = $this->getSkusWithTrash($product_sku_id);
        $sku->stock = $sku->stock + $quantity;
        $sku->save();

        $meta = ProductMeta::where('product_id', $sku->product_id)->first();
        if ($meta) {
            $meta->stock += $quantity;
            $meta->save();
        }

        return $sku;
    }

    public function decreaseStock($product_sku_id, $quantity = 1)
    {
        $sku = $this->getSkusWithTrash($product_sku_id);
        if ($sku->stock < $quantity) {
            throw new \Exception('库存不足,减库存失败');
        }
        $sku->stock = $sku->stock - $quantity;
        $sku->sales = $sku->sales + $quantity;
        $sku->save();

        $meta = ProductMeta::query()->where('product_id', $sku->product_id)->first();
        if ($meta) {
            $meta->sales += $quantity;
            $meta->stock -= $quantity;
            $meta->save();
        }

        return $sku;
    }

    public function getStock($product_sku_id)
    {
        $sku = $this->getSkusWithTrash($product_sku_id);
        return $sku->stock;
    }

    public function enoughStock($product_sku_id, $quantity)
    {
        return $this->getStock($product_sku_id) > $quantity;
    }

    /**
     * @return ProductSku
     */
    public function getAllMixAbleProductSku()
    {
        return ProductSku::query()->where('type', ProductProtocol::TYPE_OF_ENTITY)->get();
    }

    public function getMixSkus($mix_sku_id)
    {
        $sku = $this->getSkusWithTrash($mix_sku_id);
        $sku->load('mix');

        return $sku->mix;
    }

    /**
     * @param $sku_data
     * @return bool
     */
    protected function isMixProductSku($sku_data)
    {
        return count(array_get($sku_data, 'mix_skus', [])) > 0;
    }

    /**
     * @param $sku_data
     * @param $mix_sku_data
     * @param $sku
     */
    protected function attachMixSku($sku_data, $sku)
    {
        $mix_sku_data = [];
        foreach ($sku_data['mix_skus'] as $mix_sku) {
            $mix_sku_data[$mix_sku['sku_id']] = ['quantity' => $mix_sku['quantity']];
        }
        if (count($mix_sku_data)) {
            $sku->mix()->sync($mix_sku_data);
        }
    }

    public function getAllSubscribedProducts()
    {
        return Product::query()->with('skus', 'cats', 'info')->where('status', ProductProtocol::VAR_PRODUCT_STATUS_UP)->whereHas('groups', function ($query) {
            $query->where('id', CategoryProtocol::ID_OF_SUBSCRIBE_GROUP);
        })->get();
    }

    public function getMixProducts()
    {
        return Product::with('skus')->where('type', ProductProtocol::TYPE_OF_MIX)->get();
    }
}
