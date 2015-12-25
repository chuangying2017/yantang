<?php namespace App\Services\Marketing;
class TicketService {

    //用户使用优惠
    public static function used($ticket_id)
    {

        MarketingRepository::updateTicketsStatus($ticket_id, MarketingProtocol::STATUS_OF_USED);
    }
}
