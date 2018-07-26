<?php
namespace App\Repositories\Integral\OrderGenerator;


use App\Repositories\Common\Decorate\CommonDecorate;

class OrderIntegralAddress extends CommonDecorate
{
    public function handle($data, $model)
    {
        $model->integral_order()->create($this->array_data($data));

        return $this->next($data,$model);
    }

    protected function array_data(array $data)
    {
        return [
            'tel'       =>  array_get($data,'tel',''),
            'name'      =>  $data['name'],
            'phone'     =>  $data['phone'],
            'province'  =>  $data['province'],
            'city'      =>  $data['city'],
            'district'  =>  $data['district'],
            'detail'    =>  $data['detail'],
        ];
    }
}