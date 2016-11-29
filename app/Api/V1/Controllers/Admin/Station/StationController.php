<?php

namespace App\Api\V1\Controllers\Admin\Station;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Admin\CreateStationRequest;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Repositories\Counter\StationOrderCounterRepo;
use App\Repositories\Station\Staff\StaffRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use Illuminate\Http\Request;
use App\Api\V1\Requests\Station\AdminStationRequest;
use Auth;
use DB;

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
    public function index()
    {
        $stations = $this->stationRepo->getAll();
        $stations->load('counter');

        return $this->response->collection($stations, new StationTransformer());
    }


    /**
     * Station a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStationRequest $request)
    {
        $station = $this->stationRepo->createStation($request->all());
        $station['bind_token'] = generate_bind_token($station['id']);

        return $this->response->item($station, new StationTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $station = $this->stationRepo->getStation($id, true);
        $station['bind_token'] = generate_bind_token($station['id']);

        return $this->response->item($station, new StationTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateStationRequest $request, $id)
    {
        $station = $this->stationRepo->updateStation($id, $request->all());
        $station['bind_token'] = generate_bind_token($station['id']);

        return $this->response->item($station, new StationTransformer());
    }

    public function unbind(Request $request, $station_id)
    {
        $user_id = $request->input('user');

        if ($user_id == 'all') {
            $this->stationRepo->unbindAllUser($station_id);
        } else {
            $this->stationRepo->unbindUser($station_id, $user_id);
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
        $this->stationRepo->deleteStation($id);

        return $this->response->noContent();
    }

    public function setKpi(Request $request, $station_id, StationOrderCounterRepo $stationOrderCounterRepo)
    {
        $user_count_kpi = $request->input('user_count_kpi');
        $station = $this->stationRepo->getStation($station_id, false);
        $station_counter = $station->counter;
        if ($station_counter) {
            $station_counter->setUserCountKpi($user_count_kpi);
        }

        return $this->response->item($station, new StationTransformer(false));
    }
}
