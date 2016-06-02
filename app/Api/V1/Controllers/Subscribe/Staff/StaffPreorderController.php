<?php namespace App\Api\V1\Controllers\Subscribe\Staff;

use App\Api\V1\Controllers\Controller;
use App\Api\V1\Transformers\Subscribe\Station\StaffPreorderTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\Subscribe\StaffPreorder\StaffPreorderRepositoryContract;
use App\Repositories\Subscribe\Staff\StaffRepositoryContract;
use App\Api\V1\Transformers\Subscribe\Station\StaffsTransformer;
use App\Services\Subscribe\PreorderProtocol;
use App\Api\V1\Transformers\Subscribe\Station\StaffsDataTransformer;
use Auth;

class StaffPreorderController extends Controller
{
    private $user_id;
    private $staff;
    const PER_PAGE = 10;

    public function __construct(StaffRepositoryContract $staff)
    {
        $this->staff = $staff;
//        $this->user_id = Auth::user()->id();
        $this->user_id = 4;
    }

    public function index(Request $request)
    {
        $query_day = $request->input('query_day', Carbon::now());
        $staff = $this->staff->byUserId($this->user_id, true, $query_day);
        $staff->with_preorder = true;
        return $this->response->item($staff, new StaffsTransformer());
    }

    public function data(Request $request)
    {
        $daytime = $request->has('time') ? intval($request->input('time')) : null;
//        dd($daytime, isset($daytime) && is_null($daytime), is_null(0), isset($daytime));
        $query_day = $request->input('date', Carbon::now());
        $staff = $this->staff->byUserId($this->user_id, true, $query_day, $daytime);
//        dd($staff->toArray());
        if ($query_day == Carbon::now()) {
            $staff->today = true;
        }
        $staff->daytime = $daytime;

        return $this->response->item($staff, new StaffsDataTransformer());
    }

    public function update(Request $request, $staff_preorder_id, StaffPreorderRepositoryContract $staff_preorder)
    {
        $input = $request->only(['index']);
        if (empty($input['index'])) {
            $input['index'] = 0;
        }
        $staff_preorder = $staff_preorder->update($input, $staff_preorder_id);
        return $this->response->item($staff_preorder, new StaffPreorderTransformer());
    }
}