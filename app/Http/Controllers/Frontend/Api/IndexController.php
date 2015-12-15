<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Services\Client\ClientService;
use App\Services\Home\BannerService;
use App\Services\Home\NavService;
use App\Services\Home\SectionService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNav()
    {
        $nav = NavService::nav();

        return Response()->json(['data' => $nav]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBanners()
    {
        $banners = BannerService::lists();

        return Response()->json(['data' => $banners]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getSections()
    {
        $sections = SectionService::lists();

        return Response()->json(['data' => $sections]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getUserInfo()
    {
        $user_id = $this->getCurrentAuthUserId();
        $client = ClientService::show($user_id);

        $client['email'] = $client['user']['email'];
        $client['phone'] = $client['user']['phone'];

        return Response()->json(['data' => $client]);
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
