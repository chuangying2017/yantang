<?php

namespace App\Api\V1\Controllers\Admin\Station;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Admin\CreateStationRequest;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
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
        if ($request->input('reset_user')) {
            $this->stationRepo->unbindAllUser($id);
        }

        $station = $this->stationRepo->updateStation($id, $request->all());

        return $this->response->item($station, new StationTransformer());
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
}
