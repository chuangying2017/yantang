<?php namespace App\Api\V1\Transformers\Campaign;

use App\Api\V1\Transformers\Mall\ClientOrderTransformer;
use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\OrderTicket;
use App\Repositories\Product\ProductProtocol;
use League\Fractal\TransformerAbstract;

class OrderTicketTransformer extends TransformerAbstract {

    use SetInclude;

    protected $availableIncludes = ['skus', 'exchange', 'order', 'campaign'];

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
            'status' => $ticket['status']
        ];
    }

    public function includeSkus(OrderTicket $ticket)
    {
        return $this->collection($ticket->skus, new OrderTicketSkuTransformer(), true);
    }

    public function includeExchange(OrderTicket $ticket)
    {
        return $this->item($ticket->exchange, new StoreTransformer(), true);
    }

    public function includeCampaign(OrderTicket $ticket)
    {
        return $this->item($ticket->campaign, new OrderSpecialCampaignTransformer(), true);
    }

    public function includeOrder(OrderTicket $ticket)
    {
        return $this->item($ticket->order, new ClientOrderTransformer(), true);
    }

}
