<?php
namespace App\Repositories\Integral\Exchange;

use App\Models\Integral\IntegralConvertCoupon;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;

class ExchangeOperation extends ShareAccessRepositories
{

    /**
     *
     */
    public function init()
    {
        $this->set_model(new IntegralConvertCoupon());

        $this->array = ExchangeProtocol::$exchangeArray;
    }

}