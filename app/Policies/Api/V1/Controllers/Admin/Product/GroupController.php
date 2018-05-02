<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Api\V1\Transformers\Admin\Product\GroupTransformer;
use App\Http\Requests\Backend\Api\CategoryRequest;
use App\Repositories\Product\Group\GroupRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Api\V1\Controllers\Controller;


class GroupController extends Controller {

    /**
     * @var GroupRepositoryContract
     */
    private $groupRepositoryContract;

    /**
     * GroupController constructor.
     * @param GroupRepositoryContract $groupRepositoryContract
     */
    public function __construct(GroupRepositoryContract $groupRepositoryContract)
    {
        $this->groupRepositoryContract = $groupRepositoryContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = $this->groupRepositoryContract->getAll();

        return $this->response->collection($groups, new GroupTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $name = $request->input('name');
        $desc = $request->input('desc') ?: '';
        $cover_image = $request->input('cover_image') ?: '';
        $priority = $request->input('priority') ?: 0;
        $pid = $request->input('pid') ?: null;
        $group = $this->groupRepositoryContract->create($name, $desc, $cover_image, $priority, $pid);

        return $this->response->item($group, new GroupTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = $this->groupRepositoryContract->get($id);

        return $this->response->item($group, new GroupTransformer());
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
        $name = $request->input('name');
        $desc = $request->input('desc') ?: "";
        $cover_image = $request->input('cover_image');
        $priority = $request->input('priority') ?: 0;
        $pid = $request->input('pid') ?: null;
        $group = $this->groupRepositoryContract->update($id, $name, $desc, $cover_image, $priority, $pid);

        return $this->response->item($group, new GroupTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->groupRepositoryContract->delete($id);

        return $this->response->noContent();
    }
}
