<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Transformers\ChannelTransformer;
use App\Services\Product\Brand\ChannelService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChannelController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channels = ChannelService::lists(1);

        return $this->response->collection($channels, new ChannelTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $channel = ChannelService::create($request->all());

        return $this->response->created()->setContent(['data' => $channel]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $channel = ChannelService::show($id);

        return $this->response->item($channel, new ChannelTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $channel = ChannelService::update($id, $request->all());

        return $this->response->item($channel, new ChannelTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ChannelService::delete($id);

        return $this->response->noContent();
    }
}
