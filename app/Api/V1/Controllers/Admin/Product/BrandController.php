<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Api\V1\Requests\Admin\CategoryRequest;
use App\Api\V1\Transformers\Admin\Product\BrandTransformer;
use App\Repositories\Product\Brand\BrandRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\API\V1\Controllers\Controller;


class BrandController extends Controller {

    /**
     * @var BrandRepositoryContract
     */
    private $brandRepositoryContract;

    /**
     * BrandController constructor.
     * @param BrandRepositoryContract $brandRepositoryContract
     */
    public function __construct(BrandRepositoryContract $brandRepositoryContract)
    {
        $this->brandRepositoryContract = $brandRepositoryContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = $this->brandRepositoryContract->getAll();

        return $this->response->collection($brands, new BrandTransformer());
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
        $desc = $request->input('desc');
        $cover_image = $request->input('cover_image');
        $index = $request->input('index');
        $pid = $request->input('pid') ?: null;
        $brand = $this->brandRepositoryContract->create($name, $desc, $cover_image, $index, $pid);

        return $this->response->item($brand, new BrandTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brandRepositoryContract->get($id);

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
        $name = $request->input('name');
        $desc = $request->input('desc');
        $cover_image = $request->input('cover_image');
        $index = $request->input('index');
        $pid = $request->input('pid') ?: null;
        $brand = $this->brandRepositoryContract->update($id, $name, $desc, $cover_image, $index, $pid);

        return $this->response->item($brand, new BrandTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->brandRepositoryContract->delete($id);

        return $this->response->noContent();
    }
}
