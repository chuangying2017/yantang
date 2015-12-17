<?php

namespace App\Http\Controllers\Api\Frontend\Marketing;

use App\Http\Transformers\CouponTransformer;
use App\Http\Transformers\TicketTransformer;
use App\Services\Client\ClientService;
use App\Services\Marketing\Exceptions\MarketingItemDistributeException;
use Exception;

use App\Services\Marketing\MarketingItemDistributor;
use App\Services\Marketing\MarketingItemUsing;
use App\Services\Marketing\MarketingProtocol;
use App\Http\Requests\Frontend\Api\MarketingRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MarketingController extends Controller {

    /**
     * @var MarketingItemDistributor
     */
    protected $distributor;
    /**
     * @var MarketingItemUsing
     */
    protected $using;

    /**
     * @param MarketingItemDistributor $distributor
     * @param MarketingItemUsing $using
     */
    public function __construct(MarketingItemDistributor $distributor, MarketingItemUsing $using)
    {
        $this->distributor = $distributor;
        $this->using = $using;
    }


    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $status = $request->input('status', MarketingProtocol::STATUS_OF_PENDING);
            $tickets = $this->using->lists($user_id, $status);

            return $this->response->collection($tickets, new TicketTransformer());
        } catch (\Exception $e) {
            return $e->getTrace();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $resource_id = $request->input('resource_id');

        $user_info = ClientService::show($this->getCurrentAuthUserId());
        $user_info['id'] = $user_info['user_id'];

        try {
            $ticket = $this->distributor->send($resource_id, $user_info);
        } catch (MarketingItemDistributeException $e) {
            $this->response->errorBadRequest($e->getMessage());
        } catch (Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

        return $this->response->item($ticket, new TicketTransformer());
    }


}
