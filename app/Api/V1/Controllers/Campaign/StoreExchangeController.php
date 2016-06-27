<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Transformers\Campaign\OrderTicketTransformer;
use App\Repositories\OrderTicket\OrderTicketRepositoryContract;
use App\Repositories\Store\StoreRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StoreExchangeController extends Controller {

    /**
     * @var OrderTicketRepositoryContract
     */
    private $orderTicketRepo;
    /**
     * @var StoreRepositoryContract
     */
    private $storeRepo;

    /**
     * StoreExchangeController constructor.
     * @param OrderTicketRepositoryContract $orderTicketRepo
     */
    public function __construct(OrderTicketRepositoryContract $orderTicketRepo, StoreRepositoryContract $storeRepo)
    {
        $this->orderTicketRepo = $orderTicketRepo;
        $this->storeRepo = $storeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_at = $request->input('start_at') ?: null;
        $end_at = $request->input('end_at') ?: null;

        $store = $this->storeRepo->getStoreByUser(access()->id());
        $order_tickets = $this->orderTicketRepo->getOrderTicketsOfStore($store['id'], $start_at, $end_at);
        return $this->response->paginator($order_tickets, new OrderTicketTransformer());
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket_no)
    {
        $order_ticket = $this->orderTicketRepo->getOrderTicket($ticket_no);

        return $this->response->item($order_ticket, new OrderTicketTransformer());
    }

}
