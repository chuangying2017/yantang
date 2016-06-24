<?php namespace App\Api\V1\Controllers\Mall;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Mall\BrandTransformer;
use App\Repositories\Product\Brand\BrandRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class BrandController extends Controller {

    /**
     * @var BrandRepositoryContract
     */
    private $brandRepo;

    /**
     * BrandController constructor.
     * @param BrandRepositoryContract $brandRepo
     */
    public function __construct(BrandRepositoryContract $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = $this->brandRepo->getAll();
        return $this->response->collection($brands, new BrandTransformer());
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brandRepo->get($id);

        return $this->response->item($brand, new BrandTransformer());
    }
}
