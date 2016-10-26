<?php

namespace App\Api\V1\Controllers\Admin\Promotion;

use App\Api\V1\Requests\Admin\ActivityRequest;
use App\Api\V1\Transformers\Admin\Promotion\ActivityTransformer;
use App\Repositories\Activity\ActivityRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Api\V1\Controllers\Controller;

class ActivityController extends Controller {

    /**
     * @var ActivityRepository
     */
    private $activityRepo;

    /**
     * ActivityController constructor.
     * @param ActivityRepository $activityRepo
     */
    public function __construct(ActivityRepository $activityRepo)
    {

        $this->activityRepo = $activityRepo;
    }

    public function index(Request $request)
    {
        $status = $request->input('status') ?: null;
        $start_time = $request->input('start_time') ?: null;
        $end_time = $request->input('end_time') ?: null;

        $activities = $this->activityRepo->getAllPaginated($status, $start_time, $end_time);

        return $this->response->paginator($activities, new ActivityTransformer());
    }

    public function show($activity_id)
    {
        $rule = $this->activityRepo->get($activity_id, true);

        return $this->response->item($rule, new ActivityTransformer());
    }

    public function store(ActivityRequest $request)
    {
        $rule = $this->activityRepo->createActivity($request->all());

        return $this->response->item($rule, new ActivityTransformer())->setStatusCode(201);
    }

    public function update(ActivityRequest $request, $activity_id)
    {
        $rule = $this->activityRepo->updateActivity($request->all(), $activity_id);

        return $this->response->item($rule, new ActivityTransformer());
    }

    public function active(Request $request, $activity_id)
    {
        $rule = $this->activityRepo->setAsActive($activity_id);

        return $this->response->item($rule, new ActivityTransformer());
    }

    public function unactive(Request $request, $activity_id)
    {
        $rule = $this->activityRepo->setAsUnActive($activity_id);

        return $this->response->item($rule, new ActivityTransformer());
    }

}
