<?php
namespace App\Repositories\Integral\OrderHandle;

interface OrderIntegralInterface
{
    public function order_generator($order_data);
}