<?php namespace App\Http\Transformers;

use App\Models\Ticket;
use League\Fractal\TransformerAbstract;

class CouponTransformer extends TransformerAbstract {


    public function transform(Ticket $ticket)
    {
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

        $coupon = $ticket->resource;

        return array_merge(
            [
                'id'         => (int)$coupon->id,
                'name'       => $coupon->name,
                'type'       => $coupon->type,
                'detail'     => $coupon->detail,
                'content'    => (int)$coupon->content,
                'created_at' => $coupon->created_at->toDateTimeString()
            ], $limits);
    }


}
