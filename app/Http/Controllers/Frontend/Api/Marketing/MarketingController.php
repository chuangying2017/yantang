<?php

namespace App\Http\Controllers\Frontend\Api\Marketing;

use App\Services\Marketing\Exceptions\MarketingItemDistributeException;
use Exception;

use App\Services\Marketing\MarketingItemDistributor;
use App\Services\Marketing\MarketingItemUsing;
use App\Services\Marketing\MarketingProtocol;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MarketingController extends Controller {

    /**
     * @var MarketingItemDistributor
     */
    private $distributor;
    /**
     * @var MarketingItemUsing
     */
    private $using;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        #todo 获取用户id
        $user_id = 1;

        $status = $request->input('status', MarketingProtocol::STATUS_OF_PENDING);
        $tickets = $this->using->lists($user_id, $status);

        return $this->respondData($tickets);
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
            return $this->respondLogicError(400, $e->getMessage());
        } catch (Exception $e) {
            return $this->respondLogicError(400, $e->getMessage());
        }

        return $this->respondOk();
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
