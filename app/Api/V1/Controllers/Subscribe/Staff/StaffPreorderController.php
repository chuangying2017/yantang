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
use StaffService;
use App\Api\V1\Transformers\Subscribe\Staff\StaffWeeklyTransformer;
use Auth;

class StaffPreorderController extends Controller
{
    private $user_id;
    private $staff;
    const PER_PAGE = 10;

    public function __construct(StaffRepositoryContract $staff)
    {
        $this->staff = $staff;
        $this->user_id = access()->id();
    }

    public function data(Request $request)
    {
        $daytime = $request->has('time') ? intval($request->input('time')) : null;
        if ($daytime != PreorderProtocol::DAYTIME_OF_AM && $daytime != PreorderProtocol::DAYTIME_OF_PM) {
            $daytime = null;
        }
        $query_day = $request->input('date', Carbon::now());
        $data = StaffService::weeklyForStaff($query_day, $daytime);
        return $this->response->array($data);
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