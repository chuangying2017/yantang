<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\Product;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Mockery\Exception;

class ClientDetailTransformer extends TransformerAbstract {

    public function transform(Product $product)
    {
            $product_sku = $product->product_sku->first();
            $data = [
                'id'                        =>      $product['id'],
                'title'                     =>      $product['title'],
                'price'                     =>      $product['price'],
                'integral'                  =>      $product['integral'],
                'status'                    =>      $product['status'],
                'detail'                    =>      $product['detail'],
                'digest'                    =>      $product['digest'],
                'advertising'               =>      $product['advertising'],
                'remainder'                 =>      $product_sku['remainder'],//剩余量
                'postage'                   =>      array_get($product_sku,'postage',0),
                'name'                      =>      $product_sku['name'],
            ];

            if ($product->relationLoaded('specification')) {
                $data['specification']  =   $this->spec($product->specification());
            }

            if ($product->relationLoaded('images')) {
                $data['image_ides'] = array_map(function ($media_id){
                    return config('filesystems.disks.qiniu.domains.custom') . $media_id;
                },$product->images->pluck('media_id')->all());
            }

            return $data;
    }

    protected function spec($model)
    {
        if($model instanceof Model){
            foreach ($model->get(['id','type','describe']) as $item)
            {
                $argv[$item->type]  = $model->where('type',$item->type)->get(['id','type','describe']);
            }
        }else{
            throw new Exception('model not exiting',500);
        }

        return $argv;
    }
}
