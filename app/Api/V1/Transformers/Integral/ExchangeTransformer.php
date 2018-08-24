<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\IntegralConvertCoupon;
use App\Models\Integral\IntegralRecord;
use App\Repositories\Integral\Exchange\ExchangeProtocol;
use League\Fractal\TransformerAbstract;

class ExchangeTransformer extends TransformerAbstract {

    public function transform(IntegralConvertCoupon $convertCoupon)
    {
        if ($convertCoupon->relationLoaded('promotions'))
        {
            return $this->integralShow($convertCoupon);
        }

        $data = [
            'id'                =>  $convertCoupon['id'],
            'cost_integral'     =>  $convertCoupon['cost_integral'],
            'promotions_id'     =>  $convertCoupon['promotions_id'],
            'valid_time'        =>  $convertCoupon['valid_time'],
            'deadline_time'     =>  $convertCoupon['deadline_time'],
            'status'            =>  ExchangeProtocol::$status['status'][$convertCoupon['status']],
            'type'              =>  $convertCoupon['type'],
            'issue_num'         =>  $convertCoupon['issue_num'],
            'draw_num'          =>  $convertCoupon['draw_num'],
            'remain_num'        =>  $convertCoupon['remain_num'],
            'delayed'           =>  $convertCoupon['delayed'],
            'cover_image'       =>  $convertCoupon['cover_image'],
            'member_type'       =>  $convertCoupon['member_type'],
            'limit_num'         =>  $convertCoupon['limit_num'],
            'created_at'        =>  $convertCoupon['created_at'],
            'name'              =>  $convertCoupon['name'],
            'description'       =>  $convertCoupon['description'],
            'used_num'          =>  $convertCoupon['used_num'],
        ];

        return $data;
    }

    public function integralShow(IntegralConvertCoupon $convertCoupon)
    {
        $data  = [
            'id'    =>  $convertCoupon['id'],
            'name'  =>  $convertCoupon['name'],
            'description' =>    $convertCoupon['description'],
            'cost_integral' => $convertCoupon['cost_integral'],
            'couponAmount' => $convertCoupon->promotions->rules->first()->discount_content / 100,
            'cover_image' => $convertCoupon['cover_image'],
            'delayed' => $convertCoupon['delayed'],
            'status' => $this->statusGet($convertCoupon['id'],$convertCoupon['valid_time'],$convertCoupon['deadline_time'],$convertCoupon['limit_num'])
        ];

        return $data;
    }

    public function statusGet($id,$valid_time,$deadline_time,$limit_num)
    {
        $record = IntegralRecord::where('type_id','=',$id)
            ->where('record_able','=',IntegralConvertCoupon::class)
            ->where('user_id','=',access()->id())
            ->whereDate('created_at','>=',$valid_time)
            ->whereDate('created_at','<=',$deadline_time)
            ->count();
        if ($record >= $limit_num)
        {
            $status = 'gray';
        }else{
            $status = 'red';
        }
        return $status;
    }
}
