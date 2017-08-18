<?php

namespace App\Api\V1\Controllers\Admin\Residence;

use App\Api\V1\Transformers\ResidenceTransformer;
use App\Api\V1\Requests\Admin\CreateResidenceRequest;
use App\Api\V1\Requests\Admin\UpdateResidenceRequest;
use App\Repositories\Residence\ResidenceRepositoryContract;

use App\Api\V1\Controllers\Controller;
use Illuminate\Http\Request;

class ResidenceController extends Controller {

    /**
     * @var ResidenceRepositoryContract
     */
    private $residenceRepo;

    /**
     * ResidenceController constructor.
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
        $residences = $this->residenceRepo->getAllPaginated();

        return $this->response->paginator($residences, new ResidenceTransformer());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDropdown()
    {
        $residences = $this->residenceRepo->getAll();

        return $this->response->collection($residences, new ResidenceTransformer());
    }


    /**
     * Residence a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateResidenceRequest $request)
    {
        $station = $this->residenceRepo->createResidence($request->all());

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
        $station = $this->residenceRepo->getResidence($id, true);

        return $this->response->item($station, new ResidenceTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResidenceRequest $request, $id)
    {
        $station = $this->residenceRepo->updateResidence($id, $request->all());

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
        $this->residenceRepo->deleteResidence($id);

        return $this->response->noContent();
    }

    public function setGoal(Request $request, $station_id)
    {
        $goal = $request->input('goal');
        $station = $this->residenceRepo->getResidence($station_id, false);
        $station_counter = $station->counter;
        if ($station_counter) {
            $station_counter->setResidenceGoal($goal);
        }

        return $this->response->item($station, new ResidenceTransformer(false));
    }
}
