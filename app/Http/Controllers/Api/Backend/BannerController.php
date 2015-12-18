<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Home\BannerService;
use App\Http\Requests\BannerRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BannerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->input('type') ?: null;
        $banners = BannerService::lists($type);

        return $this->response->array(['data' => $banners]);
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

        $banner = BannerService::create($input);

        return $this->response->created()->setContent(['data' => $banner]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banner = BannerService::show($id);

        return $this->response->array(['data' => $banner]);
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
        $data = $request->all();

        $banner = BannerService::update($id, $data);

        return $this->response->array(['data' => $banner]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = BannerService::delete($id);

        if ( ! $count) {
            $this->response->errorNotFound();
        }

        return $this->response->noContent();
    }
}
