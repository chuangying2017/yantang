<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Product\AttrTransformer;
use App\Repositories\Product\Attribute\AttributeRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;

class AttributeController extends Controller {

    /**
     * @var AttributeRepositoryContract
     */
    private $attributeRepository;

    /**
     * AttributeController constructor.
     * @param AttributeRepositoryContract $attributeRepository
     */
    public function __construct(AttributeRepositoryContract $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \I
     * Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = $this->attributeRepository->getAllAttributes(true);

        return $this->response->collection($attributes, new AttrTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attr = $this->attributeRepository->createAttribute($request->input('name'));

        return $this->response->created()->setContent(['data' => $attr->toArray()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attr = $this->attributeRepository->getAttribute($id, true);

        return $this->response->item($attr, new AttrTransformer());
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
        $attr = $this->attributeRepository->updateAttribute($id, $request->input('name'));

        return $this->response->item($attr, new AttrTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->attributeRepository->deleteAttribute($id);

        return $this->response->noContent();
    }
}
