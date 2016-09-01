<?php

namespace App\Api\V1\Controllers\Tool;

use App\Repositories\Station\District\DistrictRepositoryContract;
use App\Services\Preorder\PreorderAssignServiceContact;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AddressController extends Controller {

    public function check(Request $request, PreorderAssignServiceContact $assignService, DistrictRepositoryContract $districtRepo)
    {
        $district = $districtRepo->get($request->input('district_id'));
        $longitude = $request->input('longitude');
        $latitude = $request->input('latitude');

        $station = $assignService->assign($longitude, $latitude, $district['id']);

        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送');
        }

        return $this->response->array($station->toArray());
    }
}
