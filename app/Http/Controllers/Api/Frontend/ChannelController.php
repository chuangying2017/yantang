<?php

namespace App\Http\Controllers\Api\Frontend;

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
        $channels = ChannelService::lists();

        return $this->response->collection($channels, new ChannelTransformer());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $channel = ChannelService::show($id);

            return $this->response->item($channel, new ChannelTransformer());
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

    }
}
