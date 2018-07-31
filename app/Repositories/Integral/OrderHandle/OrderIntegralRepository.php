<?php
namespace App\Repositories\Integral\OrderHandle;

use App\Models\Integral\IntegralOrder;
use App\Repositories\Integral\OrderFilter\OrderFilter;
use App\Repositories\Integral\OrderRule\OrderIntegralProtocol;
use Carbon\Carbon;
use Mockery\Exception;

class OrderIntegralRepository implements OrderIntegralInterface
{
    protected $order_load = ['integral_order_sku', 'integral_order_address'];

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

    public function where_express($whereData) //条件 表达式 select or get gain
    {

        $orderIntegral = IntegralOrder::query();

        $status = OrderIntegralProtocol::ORDER_STATUS_ARRAY_TIME[$whereData['status']];

        $orderIntegral->where('status','=',$whereData['status']);

        if (!empty($whereData['start_time']) && empty($whereData['end_time'])
            && Carbon::parse($whereData['start_time'])->timestamp < Carbon::now()->timestamp)
        {
            $orderIntegral -> whereDate($status,'>=',$whereData['start_time']);
        }

        if (!empty($whereData['end_time']) && empty($whereData['start_time'])
            && Carbon::parse($whereData['end_time'])->timestamp <= Carbon::today()->timestamp)
        {
            $orderIntegral -> whereDate($status,'<=', $whereData['end_time']);
        }

        if (!empty($whereData['start_time'])
            && !empty($whereData['end_time'])
            && Carbon::parse($whereData['start_time'])->timestamp
            <= Carbon::parse($whereData['end_time'])->timestamp)
        {
            $orderIntegral->whereBetween(OrderIntegralProtocol::ORDER_STATUS_ARRAY_TIME[$whereData['status']],[$whereData['start_time'],$whereData['end_time']]);
        }

        if (!empty($whereData['keywords']))
        {
           $orderIntegral->orWhere('order_no','like',"%{$whereData['keywords']}%")
               ->with(['integral_order_sku' => function ($query)use($whereData) {
                $query->orWhere('product_name','like',"%{$whereData['keywords']}%");
            },'integral_order_address' => function($query)use($whereData){
               $query->orWhere('phone','like',"%{$whereData['keywords']}%")
                   ->orWhere('name','like',"%{$whereData['keywords']}%")
                   ->orWhere('province','like',"%{$whereData['keywords']}%")
                   ->orWhere('city','like',"%{$whereData['keywords']}%")
                   ->orWhere('district','like',"%{$whereData['keywords']}%")
                   ->orWhere('detail','like',"%{$whereData['keywords']}%");
           }]);
        }else{
            $whereData['keywords'] = false;
        }

        return $this->get($orderIntegral,$this->order_load,$whereData['keywords']);
    }

    /**
     * @param $model
     * @param $keywords
     * @param array $with
     * @param int $paging
     * @return mixed
     */
    public function get($model,array $with = null,$keywords = null, $paging = 20)
    {
        if (is_array($with) && empty($keywords))
        {
            $model->with($with);
        }

        if (is_integer($paging))
        {
            return $model->paginate($paging);
        }
            return $model->get();

    }

    public function first($id)
    {
        return IntegralOrder::with($this->order_load)->find($id);
    }

    public function update_order($id,$data)
    {
        try{
            \DB::beginTransaction();

            $integral_order = IntegralOrder::find($id);

            $integral_order->status = OrderIntegralProtocol::ORDER_STATUS_DELIVERED;

            $integral_order->save();

            $integral_order->integral_order_sku()->fill(array_only($data,['express','expressOrder']))->save();

            \DB::commit();
            return $integral_order;
        }catch (Exception $exception)
        {
            \Log::error($exception->getMessage());
            \DB::rollBack();
        }
    }
}