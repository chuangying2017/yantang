<?php
namespace App\Repositories\Integral\OrderHandle;

use App\Repositories\Integral\OrderFilter\OrderFilter;
use Mockery\Exception;

class OrderIntegralRepository implements OrderIntegralInterface
{

    protected $order_filter;

    public function __construct(OrderFilter $orderFilter)
    {
        $this->order_filter         =   $orderFilter;
    }

    public function order_generator($order_data)
    {
        try{
            $filter = $this->order_filter->set_user_Id(access()->id())->set_product($order_data['product_id'])->index($order_data);

            if(!is_bool($boolean=$filter->user_compare_integral())) return $boolean;

            $this->order_filter->set_data_value();

            return $this->order_filter->order_production();
        } catch (Exception $exception)
        {
            return $exception->getMessage();
        }


    }
}