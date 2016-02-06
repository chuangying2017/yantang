<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Transformers\AgentRateTransformer;
use App\Services\Agent\AgentRepository;
use App\Services\Agent\AgentService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AgentRateController extends Controller {

    public function __construct()
    {
        $this->middleware('access.routeNeedsRole:Supervisor', ['only' => ['store', 'update']]);
    }


    public function index()
    {
        $rates = AgentRepository::listsRate();

        return $this->response->collection($rates, new AgentRateTransformer());
    }

    public function show($id)
    {
        $rate = AgentRepository::showRate($id);

        return $this->response->item($rate, new AgentRateTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $level = $request->input('level');
        $name = $request->input('name');
        $rate = $request->input('rate');

        $rate = AgentRepository::storeRate($level, $rate, $name);

        return $this->response->item($rate, new AgentRateTransformer())->setStatusCode(201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            AgentRepository::updateRates($request->input('data'));

            $rates = AgentRepository::listsRate();

            return $this->response->collection($rates, new AgentRateTransformer());
        } catch (\Exception $e ){
            $this->response->errorBadRequest($e->getMessage());
        }

    }

}
