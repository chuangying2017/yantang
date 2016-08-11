<?php namespace App\API\V1\Transformers\Promotion;
use App\Models\Promotion\Ticket;
use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract{

    public function transform(Ticket $ticket)
    {
        return $ticket->toArray();
    }
}
