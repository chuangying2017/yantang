<?php

namespace App\Api\V1\Controllers\Mall;

use App\Api\V1\Transformers\Mall\CatTransformer;
use App\Repositories\Category\CategoryRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller {

    /**
     * @var CategoryRepositoryContract
     */
    private $catRepo;

    /**
     * CategoryController constructor.
     * @param CategoryRepositoryContract $catRepo
     */
    public function __construct(CategoryRepositoryContract $catRepo)
    {
        $this->catRepo = $catRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = $this->catRepo->getAll();

        return $this->response->collection($cats, new CatTransformer());
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cats = $this->catRepo->get($id);

        return $this->response->item($cats, new CatTransformer());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
