<?php

namespace App\Api\V1\Controllers\Subscribe\Preorder;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Client\ClientRequest;
use App\Repositories\Subscribe\Address\AddressRepositoryContract;
use App\Api\V1\Requests\Subscribe\AddressRequest;
use App\Api\V1\Requests\Subscribe\CoordinateRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\AddressTransformer;
use App\Services\Subscribe\Facades\PreorderService;
use Auth;

class PreorderController extends Controller
{
    protected $address;
    protected $user_id;

    public function __construct(AddressRepositoryContract $address)
    {
        $this->address = $address;
//        $this->user_id = Auth::user()->id();
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
}