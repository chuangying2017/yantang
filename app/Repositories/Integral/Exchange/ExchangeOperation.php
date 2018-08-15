<?php
namespace App\Repositories\Integral\Exchange;

use App\Models\Integral\IntegralConvertCoupon;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;
use Carbon\Carbon;

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

    public function get_convert()
    {
      return  $this->model
          ->where('status','=',ExchangeProtocol::CONVERT_STATUS_UP)
          ->whereDate('deadline_time','>',Carbon::now()->toDateTimeString())
          ->get();
    }

    public function convertCoupon($data)
    {
        $convertRule = new ConvertRule();

        $convertRule->set_model($this->find($data['convertId']));

        $convertRule->set_VerifyData($data);


    }
}