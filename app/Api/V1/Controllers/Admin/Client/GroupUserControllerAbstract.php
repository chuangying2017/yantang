<?php namespace App\Api\V1\Controllers\Admin\Client;

use App\API\V1\Controllers\Controller;
use App\Repositories\Client\UserGroup\UserGroupRepositoryAbstract;
use Illuminate\Http\Request;
use App\Api\V1\Transformers\Admin\Access\UserTransformer;

abstract class GroupUserControllerAbstract extends Controller {

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
    public function index($group_id)
    {
        $users = $this->groupRepo->getGroupUsersPaginated($group_id);

        return $this->response->paginator($users, new UserTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $group_id)
    {
        $user_ids = $request->input('users');

        $this->groupRepo->groupAddUsers($group_id, $user_ids);

        return $this->response->created();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $group_id)
    {
        $user_ids = $request->input('users');

        if ($user_ids == 'all') {
            $this->groupRepo->groupRemoveAllUsers($group_id);
        }

        $this->groupRepo->groupRemoveUsers($group_id, $user_ids);

        return $this->response->noContent();
    }

}
