<?php namespace App\Api\V1\Controllers\Admin\Client;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Requests\Admin\UserGroupRequest;
use App\Api\V1\Transformers\Admin\Client\UserGroupTransformer;
use App\Repositories\Client\UserGroup\UserGroupRepositoryAbstract;
use Illuminate\Http\Request;

abstract class GroupControllerAbstract extends Controller {

    /**
     * GroupControllerAbstract constructor.
     * @param UserGroupRepositoryAbstract $groupRepo
     */
    public function __construct(UserGroupRepositoryAbstract $groupRepo)
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
        $groups = $this->groupRepo->getAllGroupsPaginated();

        return $this->response->paginator($groups, new UserGroupTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserGroupRequest $request)
    {
        $group = $this->groupRepo->createGroup(
            $request->input('name'),
            $request->input('priority', 0),
            $request->input('cover_image')
        );

        return $this->response->item($group, new UserGroupTransformer())->setStatusCode(201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = $this->groupRepo->updateGroup(
            $id,
            $request->input('name'),
            $request->input('priority', 0),
            $request->input('cover_image')
        );

        return $this->response->item($group, new UserGroupTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $success = $this->groupRepo->deleteGroup($id);

        return $this->response->noContent();
    }

}
