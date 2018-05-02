<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Transformers\Campaign\OrderTicketTransformer;
use App\Repositories\OrderTicket\OrderTicketRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderTicketController extends Controller {

    /**
     * @var int
     */
    protected $user_id;

    /**
     * @var OrderTicketRepositoryContract
     */
    private $ticketRepo;

    /**
     * OrderTicketController constructor.
     * @param OrderTicketRepositoryContract $ticketRepo
     */
    public function __construct(OrderTicketRepositoryContract $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
        $this->user_id = access()->id();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;

        $tickets = $this->ticketRepo->getOrderTicketsOfUser($this->user_id, $status);

        return $this->response->paginator($tickets, new OrderTicketTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket_no)
    {
        $ticket = $this->ticketRepo->getOrderTicket($ticket_no, true);
        return $this->response->item($ticket, new OrderTicketTransformer());
    }

}
