<?php
namespace App\Repositories\Integral\OrderGenerator;

use App\Repositories\Common\Decorate\CommonDecorate;

class OrderIntegralSku extends CommonDecorate
{
    public function handle($data, $model)
    {
        $model->integral_order_sku()->create($this->array_data($data));

        return $this->next($data, $model);
    }

    protected function array_data(array $data)
    {
        return [
            'product_id'        =>  $data['product_id'],
            'product_num'       =>  $data['buy_num'],
            'total_integral'    =>  $data['cost_integral'],
            'single_integral'   =>  $data['product_integral'],
            'product_name'      =>  $data['product_name'],
            'specification'     =>  $data['specification'],
        ];
    }
}