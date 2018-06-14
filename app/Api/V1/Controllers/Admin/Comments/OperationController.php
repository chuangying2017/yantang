<?php

namespace App\Api\V1\Controllers\Admin\Comments;

use App\Api\V1\Transformers\Subscribe\Station\StationTransformer;
use App\Repositories\Comment\CommentRepositoryContract;
use App\Repositories\Station\StationRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OperationController extends Controller
{
    protected $comment_contract;

    protected $stationContract;

    public function __construct(
        CommentRepositoryContract $commentRepositoryContract,
        StationRepositoryContract $stationRepositoryContract
    )
    {
        $this->comment_contract = $commentRepositoryContract;
        $this->stationContract  = $stationRepositoryContract;
    }

    //
    public function Index(Request $request){

    }

    public function show($comments_id){}

    public function update(Request $request, $comments_id){}

    public function store(Request $request){}

    public function edit($comments_id){}

    public function show_station_and_staff()
    {
        $get_all_data = $this->stationContract->getAllStation('staffs');

        return $this->response->item($get_all_data, new StationTransformer());
    }
}
