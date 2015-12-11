<?php

namespace App\Http\Controllers\Frontend\Api\Marketing;

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
        $user_id = $this->getUserId();

        $status = $request->input('status', MarketingProtocol::STATUS_OF_PENDING);
        $tickets = $this->using->lists($user_id, $status);

        return $tickets;
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

        #todo 获取用户信息
        $user_info = [
            'id'    => 1,
            'role'  => '',
            'level' => 0,
        ];

        try {
            $this->distributor->send($resource_id, $user_info);
        } catch (MarketingItemDistributeException $e) {
            $this->response->errorBadRequest($e->getMessage());
        } catch (Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

        return $this->response->created();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


}
