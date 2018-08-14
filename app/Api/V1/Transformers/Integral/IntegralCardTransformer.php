<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\IntegralCard;
use League\Fractal\TransformerAbstract;

class IntegralCardTransformer extends TransformerAbstract {

    public function transform(IntegralCard $card)
    {
        $data = [
            'card_id'   =>  $card['id'],
            'name'=>$card['name'],
            'give'=>$card['give'],
            'status'=>$card['status'],
            'type'=>$card['type'],//limits限制与loose不限制
            'mode'=>$card['mode'],//0全部会员可领取1新会员不可领取
            'cover_image'=>$card['cover_image'],
            'issue_num'=>$card['issue_num'],
            'draw_num'=>$card['draw_num'],
            'start_time'=>$card['start_time'],
            'end_time'=>$card['end_time'],
            'remain'=>$card['remain'],
            'get_member'=>$card['get_member']
        ];

        return $data;
    }
}
