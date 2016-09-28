<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Transformers\Counter\CounterTransformer;
use App\Repositories\Counter\StationOrderCounterRepo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StationBoardController extends Controller {

    /**
     * @var StationOrderCounterRepo
     */
    private $orderCounterRepo;

    /**
     * StationBoardController constructor.
     * @param StationOrderCounterRepo $orderCounterRepo
     */
    public function __construct(StationOrderCounterRepo $orderCounterRepo)
    {
        $this->orderCounterRepo = $orderCounterRepo;
    }

    public function index()
    {
        $stations = $this->orderCounterRepo->getAll();

        return $this->response->collection($stations, new CounterTransformer());
    }

}
