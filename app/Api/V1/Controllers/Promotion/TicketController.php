<?php

namespace App\Api\V1\Controllers\Promotion;

use App\API\V1\Transformers\Promotion\TicketTransformer;
use App\Repositories\Auth\User\EloquentUserRepository;
use App\Repositories\Promotion\Coupon\TicketRepositoryContract;
use App\Services\Promotion\CouponService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketController extends Controller {

    /**
     * @var TicketRepositoryContract
     */
    private $ticketRepo;

    /**
     * TicketController constructor.
     * @param TicketRepositoryContract $ticketRepo
     */
    public function __construct(TicketRepositoryContract $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

    public function index()
    {

    }

    public function store(Request $request, CouponService $couponService, EloquentUserRepository $userRepository)
    {
        $coupon_id = $request->input('coupon_id');

        $ticket = $couponService->dispatch($userRepository->setUser(access()->user()), $coupon_id);
        if (!$ticket) {
            $this->response->error($couponService->getErrorMessage('领取失败'), 200);
        }

        return $this->response->item($ticket, new TicketTransformer())->setStatusCode(201);
    }


}
