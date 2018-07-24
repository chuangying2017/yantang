<?php
namespace App\Repositories\Integral;

use App\Repositories\Integral\OrderRule\OrderIntegralProtocol;
use Illuminate\Support\Facades\Facade;

class OrderFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return OrderIntegralProtocol::class;
    }
}