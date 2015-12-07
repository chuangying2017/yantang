<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Requests\Backend\Api\BrandRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Product\Brand\BrandService;

class AdminBrandController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = BrandService::getAll();

        return $this->respondData($brands);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $name = $request->input('name');
            $cover_image = $request->input('cover_image') ?: null;
            $brand = BrandService::create($name, $cover_image);

            return $this->respondCreated($brand);
        } catch (\Exception $e) {
            return $this->respondLogicError(400, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = BrandService::show($id);

        return $this->respondData($brand);
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
        try {
            $name = $request->input('name');
            $cover_image = $request->input('cover_image') ?: null;
            $brand = BrandService::update($id, compact('name', 'cover_image'));

            return $this->respondData($brand);
        } catch (\Exception $e) {
            return $this->respondException($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response 204
     */
    public function destroy($id)
    {
        try {
            BrandService::delete($id);
        } catch (\Exception $e) {
            return $this->respondException($e);
        }

        return $this->respondDelete();
    }
}
