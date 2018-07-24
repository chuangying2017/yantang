<?php
namespace App\Repositories\Integral\OrderHandle;

use App\Repositories\Integral\OrderFilter\OrderFilter;

class OrderIntegralRepository implements OrderIntegralInterface
{

    protected $order_filter;

    public function __construct(OrderFilter $orderFilter)
    {
        $this->order_filter         =   $orderFilter;
    }

    public function order_generator($order_data)
    {
        $filter = $this->order_filter->set_user_Id(access()->id())->index($order_data);

        if(is_string($boolean=$filter->user_compare_integral())) return $boolean;


    }
}