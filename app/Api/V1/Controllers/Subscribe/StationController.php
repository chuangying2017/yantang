<?php

namespace App\Api\V1\Controllers\Subscribe;


use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\CommentTransformer;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderSkuTransformer;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Models\Comment;
use App\Models\Subscribe\Preorder;
use App\Repositories\Comment\CommentProtocol;
use App\Repositories\Comment\CommentRepositoryContract;
use App\Repositories\Station\StationProtocol;
use App\Repositories\Station\StationRepositoryContract;
use App\Api\V1\Requests\Station\BindStationRequest;
use Illuminate\Http\Request;

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
    public function info()
    {
        try {
            $station = $this->stationRepo->getStationByUser(access()->id());
            $station->load('counter');

            return $this->response->item($station, new StationTransformer());

        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), 403);
        }
    }

    /**
     * Station a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getBind(BindStationRequest $request, $station_id)
    {
        $station = $this->stationRepo->getStation($station_id);
        $station['bind_token'] = $this->stationRepo->getBindToken($station_id);

        return $this->response->item($station, new StationTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postBind(BindStationRequest $request, $station_id)
    {
        $success = $this->stationRepo->bindUser($station_id, access()->id());

        if ($success) {
            return $this->response->created();
        }

        $this->response->errorBadRequest('绑定失败');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function postUnBind(BindStationRequest $request, $station_id)
    {
        $success = $this->stationRepo->unbindUser($station_id, access()->id());

        return $this->response->noContent();
    }

    public function index()
    {
        $stations = $this->stationRepo->getAllActive();

        return $this->response->collection($stations, new StationTransformer());
    }

    public function show_station_down_all_staff_comment($staff_id){
        try{

            $result = $this->stationRepo->getAllStaffDownDataComment(StationProtocol::SELECT_STATION_DOWN_STAFF_COMMENT,$staff_id);

        }catch (\Exception $exception){
            \Log::error($exception->getMessage());
        }
            return $this->response->item($result, new PreorderTransformer());
    }

    public function show_station_comment(Request $request, CommentRepositoryContract $commentRepositoryContract)
    {
        return $this->response->array($commentRepositoryContract->station_data_dispose($request->except('token')));
    }
}
