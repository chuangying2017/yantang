<?php namespace App\Repositories\OrderTicket;
class OrderTicketProtocol {

    const TICKET_PER_PAGE = 20;

    const STATUS_OF_OK = 'ok';
    const STATUS_OF_USED = 'used';
    const STATUS_OF_EXPIRED = 'expired';

    const CHECK_STATUS_OF_HANDLED = 1;
    const CHECK_STATUS_OF_PENDING = 0;


}
