<?php

namespace App\Api\V1\Controllers\Admin\Campaign;

use App\Api\V1\Transformers\Campaign\StoreTransformer;
use App\Repositories\Store\StoreRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StoreController extends Controller {

    /**
     * @var StoreRepositoryContract
     */
    private $storeRepo;

    /**
     * StoreController constructor.
     * @param StoreRepositoryContract $storeRepo
     */
    public function __construct(StoreRepositoryContract $storeRepo)
    {
        $this->storeRepo = $storeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = $this->storeRepo->getAll();

        return $this->response->collection($stores, new StoreTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = $this->storeRepo->createStore($request->all());

        return $this->response->created()->setContent(['data' => $store->toArray()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = $this->storeRepo->getStore($id, true);

        return $this->response->item($store, new StoreTransformer());
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
        $store = $this->storeRepo->updateStore($id, $request->all());

        return $this->response->item($store, new StoreTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->storeRepo->deleteStore($id);

        return $this->response->noContent();
    }
}
