<?php namespace App\Api\V1\Controllers\Admin\Access\Permission;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Access\PermissionGroupTransformer;
use App\Repositories\Backend\Permission\Group\PermissionGroupRepositoryContract;
use Illuminate\Http\Request;

/**
 * Class PermissionGroupController
 * @package App\Http\Controllers\Access
 */
class PermissionGroupController extends Controller {

    /**
     * @var PermissionGroupRepositoryContract
     */
    protected $groups;

    /**
     * @param PermissionGroupRepositoryContract $groups
     */
    public function __construct(PermissionGroupRepositoryContract $groups)
    {
        $this->groups = $groups;
    }

    public function index(Request $request)
    {
        $groups = $this->groups->getAllGroups();

        return $this->response->collection($groups, new PermissionGroupTransformer());
    }

    /**
     * @return mixed
     */
    public function store(Request $request)
    {
        $group = $this->groups->store($request->all());
        return $this->response->item($group, new PermissionGroupTransformer());
    }


    /**
     * @param $id
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $group = $this->groups->update($id, $request->all());
        return $this->response->item($group, new PermissionGroupTransformer());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        $this->groups->destroy($id);
        return $this->response->noContent();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSort(Request $request)
    {
        $this->groups->updateSort($request->get('data'));
        return response()->json(['status' => 'OK']);
    }
}
