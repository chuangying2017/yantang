<?php

namespace App\Api\V1\Controllers\Admin\Residence;

use App\Api\V1\Transformers\ResidenceTransformer;
use App\Api\V1\Requests\Admin\CreateResidenceRequest;
use App\Repositories\Residence\ResidenceRepositoryContract;

use App\Api\V1\Controllers\Controller;
use Illuminate\Http\Request;

class ResidenceController extends Controller {

    /**
     * @var ResidenceRepositoryContract
     */
    private $residenceRepo;

    /**
     * StationController constructor.
     * @param ResidenceRepositoryContract $residenceRepo
     */
    public function __construct(ResidenceRepositoryContract $residenceRepo)
    {
        $this->residenceRepo = $residenceRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stations = $this->residenceRepo->getAll();

        return $this->response->collection($stations, new ResidenceTransformer());
    }


    /**
     * Station a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStationRequest $request)
    {
        $station = $this->residenceRepo->createStation($request->all());

        return $this->response->item($station, new ResidenceTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $station = $this->residenceRepo->getStation($id, true);

        return $this->response->item($station, new ResidenceTransformer());
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
        $station = $this->residenceRepo->updateStation($id, $request->all());

        return $this->response->item($station, new ResidenceTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->residenceRepo->deleteStation($id);

        return $this->response->noContent();
    }

    public function setGoal(Request $request, $station_id)
    {
        $goal = $request->input('goal');
        $station = $this->residenceRepo->getStation($station_id, false);
        $station_counter = $station->counter;
        if ($station_counter) {
            $station_counter->setResidenceGoal($goal);
        }

        return $this->response->item($station, new ResidenceTransformer(false));
    }
}
