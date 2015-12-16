<?php namespace App\Http\Transformers;

use App\Models\Coupon;
use App\Models\Ticket;
use App\Services\Marketing\MarketingProtocol;
use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract {

    public function transform(Ticket $ticket)
    {
        if (isset($ticket->resource) && $ticket->resource) {
            if ($ticket->resource_type == MarketingProtocol::TYPE_OF_COUPON) {
                $this->setDefaultIncludes(['coupon']);
            }
            #todo 添加其他优惠格式
        }

        $limits = [
            'quota'          => (int)$ticket->quantity_per_user,
            'roles'          => $ticket->roles,
            'level'          => $ticket->level,
            'quantity'       => (int)$ticket->quantity,
            'amount_limit'   => (int)$ticket->amount_limit,
            'category_limit' => $ticket->category_limit,
            'product_limit'  => $ticket->product_limit,
            'multi_use'      => (boolean)$ticket->multi_use,
            'effect_time'    => $ticket->effect_time,
            'expire_time'    => $ticket->expire_time,
        ];

        return array_merge(

            [
                'id'         => (int)$ticket->id,
                'ticket_no'  => $ticket->ticket_no,
                'status'     => $ticket->status,
                'billing_id' => $ticket->billing_id,
            ],
            $limits
        );


    }

    public function includeCoupon(Ticket $ticket)
    {
        $coupon = $ticket->resource;

        return $this->item($coupon, new CouponTransformer());
    }

}
