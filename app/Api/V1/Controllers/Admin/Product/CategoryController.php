<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Api\V1\Transformers\Admin\Product\CategoryTransformer;
use App\Http\Requests\Backend\Api\CategoryRequest;
use App\Repositories\Category\CategoryRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\API\V1\Controllers\Controller;


class CategoryController extends Controller {

    /**
     * @var CategoryRepositoryContract
     */
    private $categoryRepositoryContract;

    /**
     * BrandController constructor.
     * @param CategoryRepositoryContract $categoryRepositoryContract
     */
    public function __construct(CategoryRepositoryContract $categoryRepositoryContract)
    {
        $this->categoryRepositoryContract = $categoryRepositoryContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->categoryRepositoryContract->getAll();

        return $this->response->collection($categories, new CategoryTransformer());
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
        $cover_image = $request->input('cover_image');
        $priority = $request->input('priority');
        $pid = $request->input('pid') ?: null;
        $category = $this->categoryRepositoryContract->create($name, $desc, $cover_image, $priority, $pid);

        return $this->response->item($category, new CategoryTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->categoryRepositoryContract->get($id);

        return $this->response->item($category, new CategoryTransformer());
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
        $desc = $request->input('desc') ?: '';
        $cover_image = $request->input('cover_image');
        $priority = $request->input('priority');
        $pid = $request->input('pid') ?: null;
        $category = $this->categoryRepositoryContract->update($id, $name, $desc, $cover_image, $priority, $pid);

        return $this->response->item($category, new CategoryTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->categoryRepositoryContract->delete($id);

        return $this->response->noContent();
    }
}
