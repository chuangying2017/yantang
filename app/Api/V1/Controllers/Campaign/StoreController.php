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
    public function getBind(Request $request, $store_id)
    {

        if (!check_bind_token($store_id, $request->input('bind_token'))) {
            $this->response->errorForbidden('无权限查看');
        }

        $store = $this->storeRepo->getStore($store_id);

//        version('v1')->route('api.store.check.bind', [$store_id]) =  generate_bind_token($store_id);
//        return $this->response->item($store, new StoreTransformer())->setMeta($bind_token);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postBind(Request $request, $store_id)
    {
        if (!check_bind_token($store_id, $request->input('bind_token'))) {
            $this->response->errorForbidden('无权限查看');
        }

        $success = $this->storeRepo->bindUser($store_id, access()->id());

        if ($success) {
            return $this->response->created();
        }

        $this->response->errorBadRequest('绑定失败');
    }


}
