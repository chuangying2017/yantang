<?php

namespace App\Api\V1\Controllers\Campaign;

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
    public function info()
    {
        $store = $this->storeRepo->getStoreByUser(access()->id());

        return $this->response->item($store, new StoreTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
