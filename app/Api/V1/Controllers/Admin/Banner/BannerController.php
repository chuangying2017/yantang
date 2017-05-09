<?php namespace App\Api\V1\Controllers\Admin\Banner;

use App\Models\Banner;
use App\Api\V1\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Api\V1\Transformers\Admin\Banner\BannerTransformer;
use App\Services\Home\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->get('type', null);
        $banners = BannerService::lists( $type );
        return $this->response->paginator($banners, new BannerTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        BannerService::create($request->all());
        return $this->response()->noContent()->statusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return $this->response->item($banner, new BannerTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, $id)
    {
        BannerService::update($id, $request->all());
        return $this->response()->noContent()->statusCode(204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BannerService::delete( $id );
        return $this->response()->noContent()->statusCode(204);
    }
}
