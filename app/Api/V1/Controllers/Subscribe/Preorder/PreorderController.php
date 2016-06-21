<?php

namespace App\Api\V1\Controllers\Subscribe\Preorder;

use App\Api\V1\Controllers\Controller;
use App\Repositories\Subscribe\Address\AddressRepositoryContract;
use App\Api\V1\Requests\Subscribe\AddressRequest;
use App\Api\V1\Requests\Subscribe\CoordinateRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\AddressTransformer;
use App\Services\Subscribe\Facades\PreorderService;
use App\Api\V1\Requests\Subscribe\PreorderRequest;
use App\Repositories\Subscribe\Preorder\PreorderRepositoryContract;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Api\V1\Requests\Subscribe\PreorderProductRequest;
use Auth;

class PreorderController extends Controller
{
    protected $address;
    protected $user_id;
    protected $preorder;

    public function __construct(AddressRepositoryContract $address, PreorderRepositoryContract $preorder)
    {
        $this->address = $address;
        $this->preorder = $preorder;
        $this->user_id = access()->id();
    }

    public function index()
    {
        $preorder = $this->preorder->byUserId($this->user_id);
        return $this->response->item($preorder, new PreorderTransformer())->setStatusCode(201);
    }

    public function address(AddressRequest $request)
    {
        $input = $request->only(['phone', 'district', 'detail']);
        $input['user_id'] = $this->user_id;
        try {
            $address = $this->address->create($input);
        } catch (\Exception $e) {
            $this->response->errorInternal($e->getMessage());
        }
        return $this->response->item($address, new AddressTransformer);
    }

    public function stations(CoordinateRequest $request)
    {
        $input = $request->only(['longitude', 'latitude']);
        $station = PreorderService::getRecentlyStation($input['longitude'], $input['latitude']);
        return $this->response->array(['data' => $station]);
    }

    //客户创建
    public function store(PreorderRequest $request)
    {
        $input = $request->only(['name', 'phone', 'address', 'area', 'station_id']);
        $input['user_id'] = $this->user_id;
        $preorder = $this->preorder->create($input);
        return $this->response->item($preorder, new PreorderTransformer())->setStatusCode(201);
    }

    public function update(PreorderRequest $request, $preorder_id)
    {
        //status 订奶状态 pause 暂停 normal配送中
//        $input = $request->only(['status']);
//        $preorder = $this->preorder->update($input, $preorder_id);
//        return $this->response->item($preorder, new PreorderTransformer())->setStatusCode(201);
    }
}