<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Product\Category\CategoryService;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Backend\Api\CategoryRequest as Request;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = CategoryService::getAll();

            return $this->response->array(['data' => $categories]);
        } catch (\Exception $e) {
            return $e->getTrace();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $cover = $request->input('category_cover', '');
        $desc = $request->input('desc', '');
        $pid = $request->input('pid', null);

        $data = CategoryService::create($name, $cover, $desc, $pid);

        return $this->response->created(201)->setContent($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = CategoryService::show($id);

        return $this->response->array(['data' => $categories]);
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
        $category_cover = $request->input('category_cover', '');
        $desc = $request->input('desc', '');

        $data = compact('name', 'category_cover', 'desc');

        $category = CategoryService::update($id, $data);

        return $this->response->array(['data' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            CategoryService::delete($id);
        } catch (\Exception $e) {
            return $this->respondException($e);
        }

        return $this->response->noContent();
    }
}
