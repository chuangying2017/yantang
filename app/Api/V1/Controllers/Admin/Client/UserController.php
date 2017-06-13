<?php namespace App\Api\V1\Controllers\Admin\Client;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Admin\Client\ClientUserTransformer;
use App\Repositories\Client\ClientRepositoryContract;
use Illuminate\Http\Request;

class UserController extends Controller {

    /**
     * @var ClientRepositoryContract
     */
    private $clientRepo;

    /**
     * UserController constructor.
     * @param ClientRepositoryContract $clientRepo
     */
    public function __construct(ClientRepositoryContract $clientRepo)
    {
        $this->clientRepo = $clientRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $type = $request->input('type');

        if( $type == 'ordernos'){
            $order_nos = $request->input('orderNos');
            $order_nos = explode(',', $order_nos);
            $clients = $this->clientRepo->getAllClientsByOrderNo($order_nos);
        }
        else {
            $keyword = $request->input('keyword') ?: null;

            $clients = $this->clientRepo->getClientsPaginated($keyword, $status, true);
        }

        return $this->response->paginator($clients, new ClientUserTransformer());
    }

}
