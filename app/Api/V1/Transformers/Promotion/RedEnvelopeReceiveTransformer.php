<?php namespace App\Api\V1\Transformers\Promotion;

use App\Models\RedEnvelope\RedEnvelopeReceive;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class RedEnvelopeReceiveTransformer extends TransformerAbstract {

    public function transform(RedEnvelopeReceive $receive)
    {
        $this->defaultIncludes = [];

        $data = [
            'id' => $receive['id'],
            'user_id' => $receive['user_id'],
            'nickname' => $receive['nickname'],
            'avatar' => $receive['avatar'],
            'receive_at' => Carbon::parse($receive['created_at'])->toDateTimeString()
        ];

        if ($receive->relationLoaded('ticket')) {
            $this->defaultIncludes = ['ticket'];
        } else {
            $data['ticket'] = [
                'id' => $receive['ticket_id'],
                'coupon' => [
                    'id' => $receive['coupon_id'],
                    'content' => $receive['content'],
                ]
            ];
        }

        return $data;
    }

    public function includeTicket(RedEnvelopeReceive $receive)
    {
        return $this->item($receive->ticket, new TicketTransformer(), true);
    }


}
