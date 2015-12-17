<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Transformers\BrandTransformer;
use App\Http\Transformers\CartTransformer;
use App\Services\Product\Brand\BrandService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
