<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Home\NavService;
use App\Http\Requests\NavRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NavController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $navs = NavService::nav();

        return $this->response->array(['data' => $navs]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $nav = NavService::create($input);

        return $this->response->created()->setContent($nav);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nav = NavService::show($id);

        return $this->response->array(['data' => $nav]);
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
        $input = $request->all();

        $nav = NavService::update($id, $input);

        return $this->response->array(['data' => $nav]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        NavService::delete($id);

        return $this->response->noContent();
    }
}
