<?php

namespace App\Api\V1\Controllers\Admin\Integral;

use App\Services\Integral\Category\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SpecificationController extends Controller
{

    protected $category;

    public function __construct(Category $category)
    {
        $this->category=$category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->category->model_string('Specification')->get();

        return $this->response->array($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->category->CreateOrUpdate(null,$request->all(),'Specification');

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

        $array = $this->category->model_string('Specification')->find($id);

        return $this->response->array($array);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT request
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->category->CreateOrUpdate($id, $request->all(),'Specification');

        return $this->response->noContent()->statusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->category->model_string('Specification')->destroy($id);

        return $this->response->noContent()->statusCode(201);
    }
}
