<?php namespace App\Api\V1\Transformers\Campaign;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\OrderTicket;
use League\Fractal\TransformerAbstract;

class StoreTicketTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['skus', 'campaign'];

    public function transform(OrderTicket $ticket)
    {
        $this->setInclude($ticket);

        return [
            'id' => $ticket['id'],
            'user' => [
                'id' => $ticket['user_id']
            ],
            'order' => [
                'id' => $ticket['order_id']
            ],
            'ticket_no' => $ticket['ticket_no'],
            'status' => $ticket['status'],
            'settle_amount' => $ticket->skus->sum('total_amount')
        ];
    }

    public function includeSkus(OrderTicket $ticket)
    {
        return $this->collection($ticket->skus, new OrderTicketSkuTransformer(), true);
    }

    public function includeCampaign(OrderTicket $ticket)
    {
        return $this->item($ticket->campaign, new OrderSpecialCampaignTransformer(), true);
    }


}
