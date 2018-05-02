<?php

namespace App\Api\V1\Controllers\Subscribe;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Requests\Subscribe\CreateOrUpdatePreorderRequest;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderDeliverTransformer;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Repositories\Preorder\Deliver\PreorderDeliverRepositoryContract;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderAssignServiceContact;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
		//file_put_contents("test222.txt",date("Y-m-d H:i:s")."\nlog=".json_encode("dddfd")."\n\n");	
        $this->preorderRepo = $preorderRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;
		
        $orders = $this->preorderRepo->getPaginatedByUser(access()->id(), $status);
			//file_put_contents("test.txt",date("Y-m-d H:i:s")."\nlog=".json_encode($orders)."\n\n");	
        return $this->response->paginator($orders, new PreorderTransformer());
    }

    public function show($order_id)
    {
        $order = $this->preorderRepo->get($order_id, true);
		file_put_contents("test000.txt",date("Y-m-d H:i:s")."\nlog=".json_encode($order)."\n\n");	
		
        if ($order['user_id'] != access()->id()) {
            throw new AccessDeniedHttpException();
        }

        return $this->response->item($order, new PreorderTransformer());
    }

    public function deliver($order_id, PreorderDeliverRepositoryContract $deliverRepo)
    {
        $delivers = $deliverRepo->getByPreorderPaginated($order_id);

        return $this->response->paginator($delivers, new PreorderDeliverTransformer());
    }

}
