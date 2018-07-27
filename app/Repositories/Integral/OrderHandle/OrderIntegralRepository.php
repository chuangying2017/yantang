<?php
namespace App\Repositories\Integral\OrderHandle;

use App\Models\Integral\IntegralOrder;
use App\Repositories\Integral\OrderFilter\OrderFilter;
use App\Repositories\Integral\OrderRule\OrderIntegralProtocol;
use Carbon\Carbon;
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

    public function where_express($whereData)
    {
        $orderIntegral = new IntegralOrder();

        if (isset($whereData['start_time']) && !empty($whereData['start_time']))
        {

        }

        if (isset($whereData['end_time']) && !empty($whereData['end_time']))
        {

        }

        if (isset($whereData['start_time'])
            && isset($whereData['end_time'])
            && !empty($whereData['start_time'])
            && !empty($whereData['end_time'])
            && Carbon::parse($whereData['start_time'])->timestamp
            <= Carbon::parse($whereData['end_time'])->timestamp)
        {
            $orderIntegral->whereBetween(OrderIntegralProtocol::ORDER_STATUS_ARRAY_TIME[$whereData['status']],[$whereData['start_time'],$whereData['end_time']]);
        }

        if (!empty($whereData['keywords']))
        {
            $orderIntegral->where('order_no',$whereData['keywords'])->with(['integral_order_sku' => function ($query)use($whereData) {
                $query->where('product_name','like',"%{$whereData['keywords']}%");
            },'integral_order_address' => function($query)use($whereData){
                $query->where('phone','like',"%{$whereData['keywords']}%");
            }]);
        }
    }

    public function get()
    {

    }
}