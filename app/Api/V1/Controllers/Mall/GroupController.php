<?php

namespace App\Api\V1\Controllers\Mall;

use App\Api\V1\Transformers\Mall\GroupTransformer;
use App\Repositories\Product\Group\GroupRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupController extends Controller {

    /**
     * @var GroupRepositoryContract
     */
    private $groupRepo;

    /**
     * GroupController constructor.
     * @param GroupRepositoryContract $groupRepo
     */
    public function __construct(GroupRepositoryContract $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = $this->groupRepo->getAll();

        return $this->response->collection($groups, new GroupTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = $this->groupRepo->get($id);

        return $this->response->item($group, new GroupTransformer());
    }


}
