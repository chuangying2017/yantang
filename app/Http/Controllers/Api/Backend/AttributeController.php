<?php

namespace App\Http\Controllers\Api\Backend;

use App\Services\Product\Attribute\AttributeService;
use App\Http\Requests\Backend\Api\AttributeRequest as Request;

use App\Http\Requests;


class AttributeController extends BaseController {

    #todo @bryant 实现通过user id 获取 merchant id
    public static function getMerchantIdByUserId($user_id)
    {
        return 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $merchant_id = self::getMerchantIdByUserId($this->auth->id());

        $category_id = $request->input('category_id') ?: null;
        if ( ! is_null($category_id)) {
            $attributes = AttributeService::getByCategory($category_id, $merchant_id);
        } else {
            $attributes = AttributeService::findAllByMerchant($merchant_id);
        }

        return $this->respondData($attributes);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');

        $merchant_id = self::getMerchantIdByUserId($this->auth->id());

        $attribute = AttributeService::create($name, $merchant_id);

        return $this->respondCreated($attribute);

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
        $name = $request->input('name');
        $attribute = AttributeService::update($id, $name);

        return $this->respondData($attribute);
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
            AttributeService::delete($id);

        } catch (\Exception $e) {
            return $this->respondException($e);
        }

        return $this->respondDelete();
    }


    /**
     * @param Request $request
     *      - array $category_id
     * @param $attribute_id
     * @return mixed
     */
    public function bindAttributeToCategories(Request $request, $attribute_id)
    {
        $category_ids = $request->input('category_id');

        try {
            AttributeService::bindCategories($attribute_id, $category_ids);
        } catch (\Exception $e) {
            return $this->respondException($e);
        }

        return $this->respondOk();
    }

}
