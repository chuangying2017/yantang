<?php namespace App\Api\V1\Controllers\Admin\Subscribe;

use App\Api\V1\Controllers\Controller;
use App\Repositories\Subscribe\Preorder\PreorderRepositoryContract;
use Illuminate\Http\Request;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use App\Services\Subscribe\PreorderProtocol;
use StaffService;

class PreorderController extends Controller
{
    protected $preorderRepo;
    const PER_PAGE = 20;

    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    public function index(Request $request)
    {
        $order_no = $request->input('order_no', null);
        $begin_time = $request->input('begin_time', null);
        $end_time = $request->input('end_time', null);
        $phone = $request->input('phone', null);
        $status = $request->input('status', null);
        $per_page = $request->input('paginate', self::PER_PAGE);
        $preorder = $this->preorderRepo->searchInfo($per_page, $order_no, $begin_time, $end_time, $phone, $status);
        return $this->response->paginator($preorder, new PreorderTransformer());
    }

    public function allotStation(Request $request)
    {
        $preorder_id = $request->input('preorder_id');
        $input = $request->only('station_id');
        $preorder = $this->preorderRepo->byId($preorder_id);
        if ($preorder->status != PreorderProtocol::STATUS_OF_REJECT) {
            $this->response->errorInternal('该订单状态无法重新分配服务部');
        }

        $preorder = $this->preorderRepo->update($input, $preorder_id);

        return $this->response->item($preorder, new PreorderTransformer());
    }
}
