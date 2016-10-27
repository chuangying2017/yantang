<?php

namespace App\Api\V1\Controllers\Promotion;

use App\Api\V1\Transformers\Promotion\ActivityTransformer;
use App\Repositories\Activity\ActivityProtocol;
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

    public function index()
    {
        $activities = $this->activityRepo->getAllPaginated(ActivityProtocol::ACTIVITY_STATUS_OF_OK);

        return $this->response->paginator($activities, new ActivityTransformer());
    }

    public function show($activity_no)
    {
        $activity = $this->activityRepo->get($activity_no, true);

        if ($activity['status'] == ActivityProtocol::ACTIVITY_STATUS_OF_OK) {
            return $this->response->item($activity, new ActivityTransformer());
        }

        return $this->response->noContent();
    }


}
