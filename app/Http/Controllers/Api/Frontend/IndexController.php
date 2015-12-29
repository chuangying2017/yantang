<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Services\Client\ClientService;
use App\Services\Home\BannerService;
use App\Services\Home\NavService;
use App\Services\Home\SectionService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNav()
    {
        try {
            $nav = NavService::nav();
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->array(['data' => $nav]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBanners()
    {
        try {
            $banners = BannerService::lists();

            return $this->response->array(['data' => $banners]);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getSections()
    {
        try {
            $sections = SectionService::lists();
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->array(['data' => $sections]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getUserInfo()
    {
        try {
            $user_id = $this->getCurrentAuthUserId();
            $client = ClientService::show($user_id);
            $client['email'] = $client['user']['email'];
            $client['phone'] = $client['user']['phone'];

            return $this->response->array(['data' => $client]);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }
    }


}
