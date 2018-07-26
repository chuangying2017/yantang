<?php
namespace App\Repositories\Integral\OrderGenerator;


use App\Repositories\Common\Decorate\CommonDecorate;

class OrderIntegralAddress extends CommonDecorate
{
    public function handle($data, $model)
    {
        $model->integral_order_address()->create($this->array_data($data['address']));

        return $this->next($data,$model);
    }

    protected function array_data(array $data)
    {
        return [
            'tel'       =>  array_get($data,'tel',null),
            'name'      =>  $data['name'],
            'phone'     =>  $data['phone'],
            'province'  =>  $data['province'],
            'city'      =>  $data['city'],
            'district'  =>  $data['district'],
            'detail'    =>  $data['detail'],
        ];
    }
}