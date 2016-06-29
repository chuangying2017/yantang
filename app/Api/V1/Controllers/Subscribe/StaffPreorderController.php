<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StaffPreorderTransformer;
use App\Repositories\Station\StationPreorderRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class StaffPreorderController extends Controller {

    /**
     * @var StationPreorderRepositoryContract
     */
    private $orderRepo;

    /**
     * StaffPreorderController constructor.
     * @param StationPreorderRepositoryContract $orderRepo
     */
    public function __construct(StationPreorderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        $day = $request->input('day');
        $daytime = $request->input('daytime');
        $orders = $this->orderRepo->getDayPreordersOfStaff(access()->staffId(), $day, $daytime);

        return $this->response->collection($orders, new StaffPreorderTransformer());
    }

    public function show($order_id)
    {
        $order = $this->orderRepo->get($order_id, true);

        return $this->response->item($order, new PreorderTransformer());
    }


}
