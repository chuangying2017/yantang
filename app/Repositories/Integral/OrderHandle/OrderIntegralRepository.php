<?php
namespace App\Repositories\Integral\OrderHandle;

use App\Models\Integral\IntegralOrder;
use App\Repositories\Integral\OrderFilter\OrderFilter;
use App\Repositories\Integral\OrderRule\OrderIntegralProtocol;
use Carbon\Carbon;
use Mockery\Exception;

class OrderIntegralRepository implements OrderIntegralInterface
{
    protected $order_load = ['integral_order_sku.integral_product', 'integral_order_address'];

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

    public function amount_order_status($status)
    {
       return IntegralOrder::query()->where('status',$status)->count();
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
           $orderIntegral
               ->where('order_no','=',$whereData['keywords'])
            ->orWhereHas('integral_order_sku', function ($query)use($whereData) {
                $query->whereRaw("concat(`product_name`,`express`,`expressOrder`) like '%".$whereData['keywords']."%'");
            })->orWhereHas('integral_order_address', function($query)use($whereData)
               {
                   $query -> whereRaw("concat(`name`,`phone`,`province`,`city`,`district`,`detail`) like '%".$whereData['keywords']."%'");
               });

        }else{
            $whereData['keywords'] = false;
        }

        return $this->get($orderIntegral->orderBy('created_at','desc'),$this->order_load);
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

        if (is_numeric($paging))
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

            if ($integral_order->status != OrderIntegralProtocol::ORDER_STATUS_DROPSHIP)
                throw new Exception('此订单已经生成过',500);

            $integral_order->status = OrderIntegralProtocol::ORDER_STATUS_DELIVERED;

            $integral_order->save();

            $integral_order->integral_order_sku->fill(array_only($data,['express','expressOrder']))->save();

            \DB::commit();
            return $integral_order;
        }catch (Exception $exception)
        {
            \Log::error($exception->getMessage());

            \DB::rollBack();

            exit($exception->getMessage());
        }
    }

    public function user_order(array $where,$page = 1)
    {
        return IntegralOrder::query()->where($where)->with($this->order_load)->orderBy('id','desc')->forPage($page,20)->get();
    }

    public function order_update_status($order_id,$UpdateStatus = OrderIntegralProtocol::ORDER_STATUS_CONFIRM,$discern = OrderIntegralProtocol::ORDER_STATUS_DELIVERED)
    {
        $order = $this->first($order_id);

        if ($order_id->status != $discern)
        {
            throw new Exception('状态有误',500);
        }

        $order->status = $UpdateStatus;

       return $order->save();

    }
}