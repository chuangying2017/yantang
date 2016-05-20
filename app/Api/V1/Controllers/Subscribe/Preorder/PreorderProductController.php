<?php

namespace App\Api\V1\Controllers\Subscribe\Preorder;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Subscribe\PreorderProductRequest;
use Auth;
use App\Repositories\Subscribe\PreorderProduct\PreorderProductRepositoryContract;
use App\Services\Subscribe\Facades\PreorderProductService;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderProductTransformer;
use Illuminate\Http\Request;

class PreorderProductController extends Controller
{
    protected $user_id;
    protected $preorder_product;

    public function __construct(PreorderProductRepositoryContract $preorder_product)
    {
        $this->preorder_product = $preorder_product;
//        $this->user_id = Auth::user()->id();
        $this->user_id = 2;
    }

    public function index(Request $request)
    {
        $preorder_id = $request->input('preorder_id', null);
        $weekday = $request->input('weekday', null);

    }

    public function store(PreorderProductRequest $request)
    {
        $input = $request->only(['preorder_id', 'weekday', 'sku', 'daytime']);
        $preorder_product = PreorderProductService::create($input);
        return $this->response->item($preorder_product, new PreorderProductTransformer())->setStatusCode(201);
    }

    public function edit($id)
    {
        $preorder_product = $this->preorder_product->byId($id);
        return $this->response->item($preorder_product, new PreorderProductTransformer())->setStatusCode(201);
    }

    public function update(PreorderProductRequest $request, $id)
    {
        $input = $request->only(['preorder_id', 'weekday', 'sku']);
        $preorder_product = $this->preorder_product->update($id);
        return $this->response->item($preorder_product, new PreorderProductTransformer())->setStatusCode(201);
    }

    public function show()
    {

    }
}