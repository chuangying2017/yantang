<?php namespace App\Repositories\Subscribe\PreorderProduct;

use App\Models\Subscribe\PreorderProduct;

class EloquentPreorderProductRepository implements PreorderProductRepositoryContract
{

    public function moder()
    {
        return 'App\Models\Subscribe\PreorderProduct';
    }

    public function create($input)
    {
        return PreorderProduct::create($input);
    }

    public function byWhere($preorder_id)
    {
        return PreorderProduct::where('preorder_id', $preorder_id)->get();
    }

    public function byId($id)
    {
        return PreorderProduct::with('preorderProductSku')->findOrFail($id);
    }

    public function byPreorderId($preorder_id, $weekday)
    {
        return PreorderProduct::with('preorderProductSku')->where('preorder_id', '=', $preorder_id)->where('weekday', '=', $weekday)->first();
    }

    public function update($input, $id)
    {
        $preorder_product = PreorderProduct::find($id);
        $preorder_product->fill($input)->save();
        return $preorder_product;
    }
}