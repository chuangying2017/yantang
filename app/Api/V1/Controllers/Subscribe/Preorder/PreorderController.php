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
use App\Repositories\Subscribe\PreorderOrder\PreorderOrderRepositoryContract;
use App\Api\V1\Transformers\Subscribe\Preorder\PreorderOrderTransformer;
use Auth;
use DB;
use Illuminate\Http\Request;

class PreorderController extends Controller
{
    protected $address;
    protected $user_id;
    protected $preorder;
    const PER_PAGE = 5;

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


    public function stations(CoordinateRequest $request)
    {
        $input = $request->only(['longitude', 'latitude']);
        $station = PreorderService::getRecentlyStation($input['longitude'], $input['latitude']);
        return $this->response->array(['data' => $station]);
    }

    //客户创建
    public function store(PreorderRequest $request)
    {
        $input = $request->only(['phone', 'address', 'district_id', 'longitude', 'latitude']);
        $input['user_id'] = $this->user_id;
        $input['name'] = access()->user()->username;
        $station = PreorderService::getRecentlyStation($input['longitude'], $input['latitude'], $input['district_id']);
        if (empty($station)) {
            $this->response->errorInternal('提交失败,该区域没有对应的服务部');
        }
        try {
            DB::beginTransaction();
            $input['station_id'] = $station['id'];
            unset($input['longitude'], $input['latitude']);
            $preorder = $this->preorder->create($input);
            $preorder->load('district');
            DB::commit();
        } catch (\Exception $e) {
            $this->response->errorInternal('提交出错,请刷新重试');
        }
        return $this->response->item($preorder, new PreorderTransformer())->setStatusCode(201);
    }

    public function show($preorder_id)
    {
        $preorder = $this->preorder->byId($preorder_id, ['product', 'product.sku']);
        $preorder->show_product_and_sku = true;
        return $this->response->item($preorder, new PreorderTransformer());
    }


    public function preorderRecord(Request $request, PreorderOrderRepositoryContract $preorderOrderRepo)
    {
        $per_page = $request->input('paginate', self::PER_PAGE);
        $user_id = access()->id();
        $preorder = $this->preorder->byUserId($user_id);
        $where = [['field' => 'id', 'value' => $preorder->id, 'compare_type' => '=']];
        $preorder_order = $preorderOrderRepo->Paginated($per_page, $where);
        return $this->response->paginator($preorder_order, new PreorderOrderTransformer());
    }


    public function update(PreorderRequest $request, $preorder_id)
    {
        //status 订奶状态 pause 暂停 normal配送中
//        $input = $request->only(['status']);
//        $preorder = $this->preorder->update($input, $preorder_id);
//        return $this->response->item($preorder, new PreorderTransformer())->setStatusCode(201);
    }
}