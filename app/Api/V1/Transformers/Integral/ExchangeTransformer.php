<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\IntegralConvertCoupon;
use App\Repositories\Integral\Exchange\ExchangeProtocol;
use League\Fractal\TransformerAbstract;

class ExchangeTransformer extends TransformerAbstract {

    public function transform(IntegralConvertCoupon $convertCoupon)
    {
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
            'description'       =>  $convertCoupon['description']
        ];

        return $data;
    }
}
