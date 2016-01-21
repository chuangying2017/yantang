<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Services\Product\Attribute\AttributeService;
use App\Services\Product\Category\CategoryService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $decode = $request->input('expand') ? false : true;

        if ($decode) {
            $categories = CategoryService::getTree(null);
        } else {
            $categories = CategoryService::getAll();
        }

        return $this->response->array($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = CategoryService::getTree($id);

        $category->attributes = AttributeService::getByCategory($category['id']);

        return $this->response->array($category);
    }

}
