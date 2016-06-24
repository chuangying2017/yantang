<?php

namespace App\Api\V1\Controllers\Subscribe\Station;


use App\Api\V1\Controllers\Controller;
use App\Repositories\Subscribe\Staff\StaffRepositoryContract;
use App\Repositories\Subscribe\Station\StationRepositoryContract;
use Illuminate\Http\Request;
use Auth;
use App\Api\V1\Transformers\Subscribe\Station\StationPreorderTransformer;
use App\Api\V1\Transformers\Subscribe\Station\StaffPreorderTransformer;
use App\Api\V1\Requests\Subscribe\PreorderRequest;
use App\Repositories\Subscribe\Preorder\PreorderRepositoryContract;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderTransformer;
use StaffService;
use PreorderService;
use App\Services\Subscribe\PreorderProtocol;

class StationPreorderController extends Controller
{
    protected $staff;
    protected $station;
    protected $user_id;
    const PER_PAGE = 10;

    public function __construct(StaffRepositoryContract $staffs, StationRepositoryContract $station)
    {
        $this->station = $station;
        $this->user_id = access()->id();
    }

    /**
     * 查看服务部下订单列表
     */
    public function index(Request $request)
    {
        $user_id = $this->user_id;
        $type = $request->input('type', null);

        $station = $this->station->preorder($user_id, $type, self::PER_PAGE);

        return $this->response->item($station, new StationPreorderTransformer());
    }

    public function store(Request $request)
    {
        $input = $request->only(['preorder_id', 'staff_id', 'index']);
        if (empty($input['index'])) {
            $input['index'] = 0;
        }
        //staff_id空为拒绝分配到当前服务部
        if (empty($input['staff_id'])) {
            StaffService::refuse($input['preorder_id']);
            return $this->response->noContent();
        }
        try {
            $station = $this->station->getByUserId($this->user_id);
            if (!$station) {
                throw new \Exception("该会员不属于服务部");
            }
            $input['station_id'] = $station->id;
            \DB::beginTransaction();
            $staffPreorder = StaffService::assign($input);
            \DB::commit();
            return $this->response->item($staffPreorder, new StaffPreorderTransformer());
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->response->errorInternal($e->getMessage());
        }
    }

    public function update(PreorderRequest $request, $preorder_id, PreorderRepositoryContract $preorder)
    {
        //status 订奶状态 pause 暂停 normal配送中
        $input = $request->only(['status']);
        $preorder_model = $preorder->byId($preorder_id);
        if (empty($preorder_model)) {
            $this->response->errorInternal('修改的订奶配置不存在');
        }
        if ($input['status'] != PreorderProtocol::STATUS_OF_NORMAL && $input['status'] != PreorderProtocol::STATUS_OF_PAUSE) {
            $this->response->errorInternal('操作只能为停止配送或者恢复配送');
        }
        try {
            \DB::beginTransaction();
            $preorder_model = PreorderService::updateStatus($input, $preorder_id);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->response->errorInternal($e->getMessage());
        }
        return $this->response->item($preorder_model, new PreorderTransformer())->setStatusCode(201);
    }
}
