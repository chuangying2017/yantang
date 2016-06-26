<?php

namespace App\Api\V1\Controllers\Subscribe;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use App\Api\V1\Requests\Station\BindStationRequest;

class StationController extends Controller {

    /**
     * @var StationRepositoryContract
     */
    private $stationRepo;

    /**
     * StationController constructor.
     * @param StationRepositoryContract $stationRepo
     */
    public function __construct(StationRepositoryContract $stationRepo)
    {
        $this->stationRepo = $stationRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        try {
            $station = $this->stationRepo->getStationByUser(access()->id());

            return $this->response->item($station, new StationTransformer());
        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), $e->getCode());
        }

    }

    /**
     * Station a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getBind(BindStationRequest $request, $station_id)
    {
        $station = $this->stationRepo->getStation($station_id);
        $station['bind_token'] = $this->stationRepo->getBindToken($station_id);

        return $this->response->item($station, new StationTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postBind(BindStationRequest $request, $station_id)
    {
        $success = $this->stationRepo->bindUser($station_id, access()->id());

        if ($success) {
            return $this->response->created();
        }

        $this->response->errorBadRequest('绑定失败');
    }

    public function index()
    {
        $stations = $this->stationRepo->getAllActive();

        return $this->response->collection($stations, new StationTransformer());
    }

}
