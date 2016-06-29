<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\API\V1\Controllers\Controller;
use App\Api\V1\Requests\Subscribe\CreateOrUpdatePreorderRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderAssignServiceContact;
use Illuminate\Http\Request;

use App\Http\Requests;

class PreorderController extends Controller {

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    /**
     * PreorderController constructor.
     * @param PreorderRepositoryContract $preorderRepo
     */
    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;

        $orders = $this->preorderRepo->getPaginatedByUser(access()->id(), $status);

        return $this->response->paginator($orders, new PreorderTransformer());
    }

    public function store(CreateOrUpdatePreorderRequest $request, PreorderAssignServiceContact $assignService)
    {
        $data = [
            'user_id' => access()->id(),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'district_id' => $request->input('district'),
            'address' => $request->input('address'),
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
        ];

        $station = $assignService->assign($data['longitude'], $data['latitude'], $data['district_id']);

        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送');
        }

        $data['station_id'] = $station['id'];

        $order = $this->preorderRepo->createPreorder($data);

        return $this->response->item($order, new PreorderTransformer())->setStatusCode(201);
    }

    public function show($order_id)
    {
        $order = $this->preorderRepo->get($order_id, true);

        return $this->response->item($order, new PreorderTransformer());
    }

    public function update(CreateOrUpdatePreorderRequest $request, $order_id, PreorderAssignServiceContact $assignService)
    {
        $order = $this->preorderRepo->get($order_id);

        if ($order['user_id'] !== access()->id()) {
            $this->response->errorForbidden('没有权限修改该订单');
        }

        $data = [
            'user_id' => access()->id(),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'district_id' => $request->input('district'),
            'address' => $request->input('address'),
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
        ];

        $station = $assignService->assign($data['longitude'], $data['latitude'], $data['district_id']);

        if (!$station) {
            $this->response->errorNotFound('该地址暂时未支持配送');
        }

        $data['station_id'] = $station['id'];

        try {
            $order = $this->preorderRepo->updatePreorder($order_id, $data);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), 400);
        }

        return $this->response->item($order, new PreorderTransformer());
    }

}
