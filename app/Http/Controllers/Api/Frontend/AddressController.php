<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Transformers\AddressTransformer;
use App\Services\Client\AddressService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AddressController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = $this->getCurrentAuthUserId();

        $data = AddressService::fetchByUser($user_id);

        return $this->response->collection($data, new AddressTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->input('data');
            $data['user_id'] = $this->getCurrentAuthUserId();

            $address = AddressService::create($data);

            return $this->response->item($address, new AddressTransformer());
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address = AddressService::show($id);

        return $this->response->item($address, new AddressTransformer());
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
        $data = $request->input('data');

        $address = AddressService::update($id, $data);

        return $this->response->item($address, new AddressTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
