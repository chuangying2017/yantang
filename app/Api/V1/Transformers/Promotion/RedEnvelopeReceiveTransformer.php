<?php namespace App\Api\V1\Transformers\Promotion;

use App\Models\RedEnvelope\RedEnvelopeReceive;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class RedEnvelopeReceiveTransformer extends TransformerAbstract {

    public function transform(RedEnvelopeReceive $receive)
    {
        return [
            'id' => $receive['id'],
            'user_id' => $receive['user_id'],
            'nickname' => $receive['nickname'],
            'avatar' => $receive['avatar'],
            'coupon' => [
                'id' => $receive['coupon_id'],
                'content' => $receive['content'],
            ],
            'ticket' => [
                'ticket_id' => $receive['ticket_id']
            ],
            'receive_at' => Carbon::parse($receive['created_at'])->toDateTimeString()
        ];
    }


}
