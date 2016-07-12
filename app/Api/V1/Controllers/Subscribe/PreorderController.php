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

    public function show($order_id)
    {
        $order = $this->preorderRepo->get($order_id, true);

        return $this->response->item($order, new PreorderTransformer());
    }

}
