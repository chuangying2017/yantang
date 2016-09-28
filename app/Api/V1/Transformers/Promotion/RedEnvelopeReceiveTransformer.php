<?php namespace App\Api\V1\Transformers\Promotion;

use App\Models\RedEnvelope\RedEnvelopeReceive;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class RedEnvelopeReceiveTransformer extends TransformerAbstract {

    public function transform(RedEnvelopeReceive $receive)
    {
        $data = [
            'id' => $receive['id'],
            'user_id' => $receive['user_id'],
            'nickname' => $receive['nickname'],
            'avatar' => $receive['avatar'],
            'ticket' => [
                'ticket_id' => $receive['ticket_id']
            ],
            'receive_at' => Carbon::parse($receive['created_at'])->toDateTimeString()
        ];

        if ($receive->relationLoaded('coupon')) {
            $this->defaultIncludes = ['coupon'];
        } else {
            $data['coupon'] = [
                'id' => $receive['coupon_id'],
                'content' => $receive['content'],
            ];
        }

        return $data;
    }

    public function includeCoupon(RedEnvelopeReceive $receive)
    {
        return $this->item($receive->coupon, new CouponTransformer(), true);
    }


}
