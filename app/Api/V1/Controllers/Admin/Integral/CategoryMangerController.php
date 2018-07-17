<?php

namespace App\Api\V1\Controllers\Admin\Integral;

use App\Api\V1\Transformers\Integral\IntegralTransformer;
use App\Services\Integral\Category\IntegralCategoryMangers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryMangerController extends Controller
{
    protected $category;

    public function __construct(IntegralCategoryMangers $integralCategory)
    {
        $this->category=$integralCategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category_data = $this->category->select();

        return $this->response->item($category_data, new IntegralTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->category->CreateOrUpdate(null, $request->input());

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category_data = $this->category->select(['id'=>$id]);

        return $this->response->item($category_data, new IntegralTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function edit($id, Request $request)
    {

        $category = $this->category->CreateOrUpdate($id,$request->input());

        return $this->response->item($category, new IntegralTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->category->delete($id);

        return $this->response->noContent()->statusCode(201);
    }
}
