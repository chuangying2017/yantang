<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Transformers\FavTransformer;
use App\Services\ApiConst;
use App\Services\Product\Fav\FavService;
use App\Http\Requests\Frontend\FavRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FavController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = $this->getCurrentAuthUserId();
        $data = FavService::lists($user_id, ApiConst::FAV_PRE_PAGE);

        return $this->response->paginator($data, new FavTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $this->getCurrentAuthUserId();
        $product_id = $request->input('product_id');

        $fav = FavService::create($user_id, $product_id);

        if($fav) {
            return $this->response->created();
        }

        return $this->response->noContent();

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = $this->getCurrentAuthUserId();
        FavService::delete($user_id, $id);

        return $this->response->noContent();
    }
}
