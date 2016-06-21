<?php

namespace App\Api\V1\Controllers\Subscribe\Preorder;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Subscribe\PreorderProductRequest;
use Auth;
use App\Repositories\Subscribe\PreorderProduct\PreorderProductRepositoryContract;
use PreorderProductService;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderProductTransformer;
use Illuminate\Http\Request;
use DB;

class PreorderProductController extends Controller
{
    protected $user_id;
    protected $preorder_product;

    public function __construct(PreorderProductRepositoryContract $preorder_product)
    {
        $this->preorder_product = $preorder_product;
        $this->user_id = access()->id();
    }

    public function index(Request $request)
    {
        $preorder_id = $request->input('preorder_id', null);
        $weekday = $request->input('weekday', null);
        $preorder_product = $this->preorder_product->byPreorderId($preorder_id, $weekday);
        return $this->response->item($preorder_product, new PreorderProductTransformer())->setStatusCode(201);
    }

    public function store(PreorderProductRequest $request)
    {
        $input = $request->only(['preorder_id', 'weekdays']);
        try {
            DB::beginTransaction();
            $preorder_product = PreorderProductService::batchOperation($input);
            DB::commit();
            return $this->response->item($preorder_product, new PreorderProductTransformer())->setStatusCode(201);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->response->errorInternal($e->getMessage());
        }
    }

    public function show($id)
    {
        $preorder_product = $this->preorder_product->byId($id);
        return $this->response->item($preorder_product, new PreorderProductTransformer())->setStatusCode(201);
    }

    public function update(PreorderProductRequest $request)
    {
        $input = $request->only(['preorder_id', 'weekdays']);
        try {
            DB::beginTransaction();
            $preorder_product = PreorderProductService::batchOperation($input);
            DB::commit();
            return $this->response->item($preorder_product, new PreorderProductTransformer())->setStatusCode(201);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->response->errorInternal($e->getMessage());
        }
    }

}