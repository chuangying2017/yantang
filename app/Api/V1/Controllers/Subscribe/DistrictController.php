<?php namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
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

}
