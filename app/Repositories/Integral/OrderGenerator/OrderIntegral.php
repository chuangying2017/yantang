<?php
namespace App\Repositories\Integral\OrderGenerator;

use App\Repositories\Common\Decorate\CommonDecorate;
use App\Repositories\Integral\OrderFacade;
use App\Repositories\Integral\OrderRule\OrderIntegralProtocol;


class OrderIntegral extends CommonDecorate
{

    public function handle($data, $model)
    {
        $model->create($this->array_data($data));

        return $this->next($data,$model);
    }

    protected function array_data(array $data)
    {
        return [
            'order_no'                  =>  OrderFacade::order_generator(),
            'user_id'                   =>  access()->id(),
            'status'                    =>  OrderIntegralProtocol::ORDER_STATUS_DROPSHIP,
            'cost_integral'             =>  array_get($data,'cost_integral',0),
            'pay_channel'               =>  OrderIntegralProtocol::ORDER_CHANNEL_PAY,
        ];
    }
}