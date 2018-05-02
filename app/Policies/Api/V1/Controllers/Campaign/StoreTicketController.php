<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Transformers\Campaign\StoreTicketTransformer;
use App\Repositories\OrderTicket\OrderTicketRepositoryContract;
use App\Services\OrderTicket\OrderTicketManageContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StoreTicketController extends Controller {

    /**
     * @var OrderTicketRepositoryContract
     */
    private $orderTIcketRepo;
    /**
     * @var OrderTicketManageContract
     */
    private $orderTicketManage;

    /**
     * StoreTicketController constructor.
     * @param OrderTicketRepositoryContract $orderTicketRepo
     */
    public function __construct(OrderTicketRepositoryContract $orderTicketRepo, OrderTicketManageContract $orderTicketManage)
    {
        $this->orderTIcketRepo = $orderTicketRepo;
        $this->orderTicketManage = $orderTicketManage;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket_no)
    {
        $ticket = $this->orderTIcketRepo->getOrderTicket($ticket_no, true);

        return $this->response->item($ticket, new StoreTicketTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ticket_no)
    {
        $order_ticket = $this->orderTicketManage->exchange($ticket_no, access()->storeId());

        return $this->response->item($order_ticket, new StoreTicketTransformer());
    }

}
