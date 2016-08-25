<?php

namespace App\Api\V1\Requests\Station;

use App\Http\Requests\Request;
use App\Repositories\Station\StationRepositoryContract;

class BindStationRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(StationRepositoryContract $stationRepo)
    {
        return $stationRepo->getBindToken($this->route()->getParameter('station_id')) == $this->input('bind_token');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bind_token' => 'required'
        ];
    }
}
