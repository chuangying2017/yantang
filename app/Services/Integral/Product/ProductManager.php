<?php
namespace App\Services\Integral\Product;

use App\Models\Integral\Product;
use App\Repositories\Common\config\DispatchClass;
use App\Repositories\Integral\Decorate\Images;
use App\Repositories\Integral\Decorate\ProductAttribute;
use App\Repositories\Integral\Decorate\ProductCats;
use App\Repositories\Integral\Decorate\ProductData;
use App\Repositories\Integral\Decorate\ProductSku;
use Illuminate\Support\Facades\DB;

class ProductManager implements ProductInerface
{

    public function select()
    {
        // TODO: Implement select() method.
    }

    /**
     * @param array $data
     * @param int|null $id
     * @return
     */
    public function createOrUpdate(array $data = [], $id = null)
    {
           $handler = $this->get_handle_config();

           if(is_numeric($id)){
               $product = $this->get_product($id);
           }else{
               $product = new Product();
           }

           $productResult = \DB::transaction(function ()use ($handler,$data,$product){
               return $handler->handle($data,$product);
           });

           return $productResult;
    }

    /**
     * @param $attach
     * @return boolean
     */
    public function delete($attach)
    {
       $product = $this->get_product($attach,false);
       return DB::transaction(
       /**
        * @return bool|null
        */
           function ()use($product){
           $product->images()->detach();
           $product->integral_category()->detach();
           $product->product_sku()->delete();
           $product->specification()->detach();
           return $product->delete();
        });
    }

    public function edit()
    {

    }

    public function get_handle_config()
    {
        $config = [
            ProductData::class,
            ProductCats::class,
            ProductSku::class,
            ProductAttribute::class,
            Images::class,
        ];
        return DispatchClass::get_container($config);
    }

    public function get_product($id,$with_detail=true)
    {
        $product = Product::query()->findOrFail($id);

        if($with_detail){
            $product->load('integral_category','integral_sku','images');
        }

        return $product;
    }

    public function array_product($data)
    {
        return array_only($data, $this->array_config_rule());
    }

    public function array_config_rule(array $data=[])
    {
        if(empty($data)){
            $data = ProductProtocol::INTEGRAL_PRODUCT_INSERTION_RULE_ARRAY;
        }
        return $data;
    }

}