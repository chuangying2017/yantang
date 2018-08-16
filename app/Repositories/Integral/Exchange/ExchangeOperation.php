<?php
namespace App\Repositories\Integral\Exchange;

use App\Models\Integral\IntegralConvertCoupon;
use App\Repositories\Integral\ShareCarriageWheel\ShareAccessRepositories;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

        try {
            $this->ConvertFunc($convertRule);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $e->getMessage();
        }
    }

    /**
     * @param $classVerify
     * @throws \Exception
     */
    protected function ConvertFunc($classVerify)
    {
        foreach ($classVerify->function as $value)
        {
            $stringOrBoolean = $classVerify->{$value}();

            if (is_string($stringOrBoolean))
            {
                throw new \Exception($stringOrBoolean,500);
            }
        }
    }

    public function generate_data()
    {

    }


}