<?php namespace App\Api\V1\Controllers\Admin\Station;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\DistrictTransformer;
use App\Repositories\Station\District\DistrictRepositoryContract;
use Illuminate\Http\Request;

class DistrictController extends Controller {

    /**
     * @var DistrictRepositoryContract
     */
    private $districtRepo;

    /**
     * DistrictController constructor.
     * @param DistrictRepositoryContract $districtRepo
     */
    public function __construct(DistrictRepositoryContract $districtRepo)
    {
        $this->districtRepo = $districtRepo;
    }

    public function index()
    {
        $districts = $this->districtRepo->getAll();
        return $this->response->collection($districts, new DistrictTransformer());
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $district = $this->districtRepo->create($name);
        return $this->response->item($district, new DistrictTransformer())->setStatusCode(201);
    }


    public function update(Request $request, $id)
    {
        $name = $request->only('name');
        $district = $this->districtRepo->update($id, $name);
        return $this->response->item($district, new DistrictTransformer());
    }

    public function destroy($id)
    {
        $success = $this->districtRepo->delete($id);

        return $this->response->noContent();
    }
}
