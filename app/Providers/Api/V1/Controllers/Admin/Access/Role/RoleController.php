<?php namespace App\Api\V1\Controllers\Admin\Access\Role;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Access\RoleTransformer;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use Illuminate\Http\Request;

/**
 * Class RoleController
 * @package App\Http\Controllers\Access
 */
class RoleController extends Controller {

    /**
     * @var RoleRepositoryContract
     */
    protected $roles;

    /**
     * @var PermissionRepositoryContract
     */
    protected $permissions;

    /**
     * @param RoleRepositoryContract $roles
     * @param PermissionRepositoryContract $permissions
     */
    public function __construct(RoleRepositoryContract $roles, PermissionRepositoryContract $permissions)
    {
        $this->roles = $roles;
        $this->permissions = $permissions;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $roles = $this->roles->getAllRoles();

        return $this->response->collection($roles, new RoleTransformer());
    }


    /**
     * @return mixed
     */
    public function store(Request $request)
    {
        $role = $this->roles->create($request->all());
        return $this->response->item($role, new RoleTransformer())->setStatusCode(201);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $role = $this->roles->update($id, $request->all());
        return $this->response->item($role, new RoleTransformer());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        $this->roles->destroy($id);
        return $this->response->noContent();
    }
}
