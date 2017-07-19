<?php

namespace App\Api\V1\Controllers\Promotion;

use App\Api\V1\Transformers\Promotion\TicketTransformer;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Promotion\CouponService;
use App\Services\Promotion\PromotionProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GiftcardController extends Controller {

    /**
     * @var TicketRepositoryContract
     */
    private $ticketRepo;

    /**
     * GiftcardController constructor.
     * @param TicketRepositoryContract $ticketRepo
     */
    public function __construct(TicketRepositoryContract $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: PromotionProtocol::STATUS_OF_TICKET_OK;
        $tickets = $this->ticketRepo->getGiftcardOfUserPaginated(access()->id(), $status);

        return $this->response->paginator($tickets, new TicketTransformer());
    }

}
