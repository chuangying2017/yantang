<?php

namespace App\Api\V1\Controllers\Admin\Product;

use App\Repositories\Product\Attribute\AttributeValueRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Api\V1\Controllers\Controller;


class AttributeValueController extends Controller {

    /**
     * @var AttributeValueRepositoryContract
     */
    private $attributeValueRepositoryContract;

    /**
     * AttributeValueController constructor.
     * @param AttributeValueRepositoryContract $attributeValueRepositoryContract
     */
    public function __construct(AttributeValueRepositoryContract $attributeValueRepositoryContract)
    {
        $this->attributeValueRepositoryContract = $attributeValueRepositoryContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($attr_id)
    {
        $values = $this->attributeValueRepositoryContract->getAllValuesOfAttributes($attr_id);

        return $this->response->array(['data' => $values->toArray()]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $attr_id)
    {
        $value = $this->attributeValueRepositoryContract->createAttribute($attr_id, $request->input('name'));

        return $this->response->created(null, ['data' => $value->toArray()]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $attr_id, $value_id)
    {
        $value = $this->attributeValueRepositoryContract->updateAttribute($value_id, $request->input('name'));

        return $this->response->array(['data' => $value->toArray()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->attributeValueRepositoryContract->deleteAttribute($id);

        return $this->response->noContent();
    }
}
