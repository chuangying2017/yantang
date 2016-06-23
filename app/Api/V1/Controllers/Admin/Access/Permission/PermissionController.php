<?php namespace App\Api\V1\Controllers\Admin\Access\Permission;

use App\Api\V1\Transformers\Admin\Access\PermissionTransformer;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Repositories\Backend\Permission\Group\PermissionGroupRepositoryContract;
use Illuminate\Http\Request;

/**
 * Class PermissionController
 * @package App\Http\Controllers\Access
 */
class PermissionController extends BackendController {

    /**
     * @var RoleRepositoryContract
     */
    protected $roles;

    /**
     * @var PermissionRepositoryContract
     */
    protected $permissions;

    /**
     * @var PermissionGroupRepositoryContract
     */
    protected $groups;

    /**
     * @param RoleRepositoryContract $roles
     * @param PermissionRepositoryContract $permissions
     * @param PermissionGroupRepositoryContract $groups
     */
    public function __construct(RoleRepositoryContract $roles, PermissionRepositoryContract $permissions, PermissionGroupRepositoryContract $groups)
    {
        $this->roles = $roles;
        $this->permissions = $permissions;
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $permissions = $this->permissions->getPermissionsPaginated(50);
        return $this->response->paginator($permissions, new PermissionTransformer());
    }


    /**
     * @return mixed
     */
    public function store(Request $request)
    {
        $permission = $this->permissions->create($request->except('permission_roles'), $request->only('permission_roles'));
        return $this->response->item($permission, new PermissionTransformer());
    }


    /**
     * @param $id
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $permission = $this->permissions->update($id, $request->except('permission_roles'), $request->only('permission_roles'));
        return $this->response->item($permission, new PermissionTransformer());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        $this->permissions->destroy($id);
        return $this->response->noContent();
    }
}
