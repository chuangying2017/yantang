<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Requests\Backend\Api\BrandRequest as Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Transformers\BrandTransformer;
use App\Services\Product\Brand\BrandService;

class BrandController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $category_id = $request->input('category_id') ?: null;
            if ( ! is_null($category_id)) {
                $brands = BrandService::getByCategory($category_id);
            } else {
                $brands = BrandService::getAll();
            }

            return $this->response->collection($brands, new BrandTransformer());
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
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
        try {
            $name = $request->input('name');
            $cover_image = $request->input('cover_image') ?: null;
            $brand = BrandService::create($name, $cover_image);

            return $this->response->created();
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
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

        return $this->response->item($brand, new BrandTransformer());
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

            return $this->response->item($brand, new BrandTransformer());
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
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

        return $this->response->noContent();
    }

    /**
     * 绑定品牌所属分类
     * @param Request $request
     * @param $brand_id
     * @return mixed
     */
    public function bindBrandToCategories(Request $request, $brand_id)
    {
        $category_ids = $request->input('category_id');

        try {
            BrandService::bindCategory($brand_id, $category_ids);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }

        return $this->response->accepted();
    }


}
