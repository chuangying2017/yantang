<?php namespace App\Api\V1\Controllers\Admin\Access;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Access\UserTransformer;
use App\Repositories\Backend\AccessProtocol;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Backend\User\UserContract;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function __construct(UserContract $users, RoleRepositoryContract $roles, PermissionRepositoryContract $permissions)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->permissions = $permissions;
    }

    /**
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status') ?: AccessProtocol::USER_STATUS_OF_OK;

        $users = $this->users->getUsersPaginated(config('access.users.default_per_page'), $status);

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @return mixed
     */
    public function store(Request $request)
    {
        $user = $this->users->create(
            $request->except('assignees_roles', 'permission_user'),
            $request->only('assignees_roles'),
            $request->only('permission_user')
        );
        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $user = $this->users->findOrThrowException($id, true);
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $user = $this->users->update($id,
            $request->except('assignees_roles', 'permission_user'),
            $request->only('assignees_roles'),
            $request->only('permission_user')
        );

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        $this->users->destroy($id);
        return $this->response->noContent();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id, Request $request)
    {
        $this->users->restore($id);
        return $this->response->created();
    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
    public function mark($id, $status, Request $request)
    {
        $this->users->mark($id, $status);
        return $this->show($id);
    }


    /**
     * @return mixed
     */
    public function deleted()
    {
        $users = $this->users->getDeletedUsersPaginated(25);
        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function updatePassword($id, Request $request)
    {
        $this->users->updatePassword($id, $request->all());
        return $this->show($id);
    }


}
