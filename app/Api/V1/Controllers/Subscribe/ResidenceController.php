<?php namespace App\Api\V1\Controllers\Subscribe;

use App\Models\Residence;
use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\ResidenceTransformer;
use App\Repositories\Residence\ResidenceRepositoryContract;
use Illuminate\Http\Request;

class ResidenceController extends Controller {

    /**
     * @var ResidenceRepositoryContract
     */
    private $residenceRepo;

    /**
     * residenceController constructor.
     * @param ResidenceRepositoryContract $residenceRepo
     */
    public function __construct(ResidenceRepositoryContract $residenceRepo)
    {
        $this->residenceRepo = $residenceRepo;
    }

    public function show(Residence $residence)
    {
        return $this->response->item($residence, new ResidenceTransformer());
    }

}
