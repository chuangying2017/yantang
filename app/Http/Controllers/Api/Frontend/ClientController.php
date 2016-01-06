<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Services\Client\ClientService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClientController extends Controller {

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $client = ClientService::show($user_id);
            $client['email'] = $client['user']['email'];
            $client['phone'] = $client['user']['phone'];

            return $this->response->array(['data' => $client]);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $data = $request->all();
            $client = ClientService::update($user_id, $data);
            $client['email'] = $client['user']['email'];
            $client['phone'] = $client['user']['phone'];

            return $this->response->array(['data' => $client]);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
