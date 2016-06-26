<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Requests\Campaign\BindStoreRequest;
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
    public function getBind(BindStoreRequest $request, $store_id)
    {
        $store = $this->storeRepo->getStore($store_id);
        $store['bind_token'] = $this->storeRepo->getBindToken($store_id);

        return $this->response->item($store, new StoreTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postBind(BindStoreRequest $request, $store_id)
    {
        $success = $this->storeRepo->bindUser($store_id, access()->id());

        if ($success) {
            return $this->response->created();
        }

        $this->response->errorBadRequest('绑定失败');
    }

    public function index()
    {
        $stores = $this->storeRepo->getAllActive();

        return $this->response->collection($stores, new StoreTransformer());
    }

}
