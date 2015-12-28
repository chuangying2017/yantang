<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Product\Group\GroupService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $groups = GroupService::lists();

        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->array($groups);
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
            $data = $request->all();
            $group = GroupService::create($data);

            return $this->response->created()->setContent(['data' => $group]);
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
        try {
            $group = GroupService::show($id);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->array($group);
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
        try {
            $data = $request->all();

            $group = GroupService::update($id, $data);

            return $this->response->array($group);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            GroupService::delete($id);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->noContent();
    }

    public function bindingProducts(Request $request, $group_id)
    {
        try {
            $product_ids = $request->input('product_ids');

            GroupService::bindProducts($group_id, $product_ids);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->created([]);
    }

}
