<?php namespace App\API\V1\Transformers\Promotion;

use App\API\V1\Transformers\Admin\Promotion\CouponTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\Promotion\Ticket;
use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['coupon'];

    public function transform(Ticket $ticket)
    {
        $this->setInclude($ticket);
        return $ticket->toArray();
    }

    public function includeCoupon(Ticket $ticket)
    {
        return $this->item($ticket['coupon'], new CouponTransformer(), true);
    }
}
