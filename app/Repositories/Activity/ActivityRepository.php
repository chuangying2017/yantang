<?php namespace App\Repositories\Activity;

use App\Models\Promotion\Activity;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use Illuminate\Contracts\Pagination\Paginator;

class ActivityRepository {

    /**
     * @param $data
     * @return array
     */
    protected function filterData($data)
    {
        return [
            'name' => $data['name'],
            'desc' => array_get($data, 'desc', ''),
            'priority' => array_get($data, 'priority', 1),
            'cover_image' => $data['cover_image'],
            'background_color' => $data['background_color'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'coupons' => $data['coupons'],
            'share_image' => $data['share_image'],
            'share_friend_title' => $data['share_friend_title'],
            'share_desc' => $data['share_desc'],
            'share_moment_title' => $data['share_moment_title'],
            'can_share' => array_get($data, 'can_share', ActivityProtocol::ACTIVITY_SHARE_OF_OK),
            'status' => array_get($data, 'status', ActivityProtocol::ACTIVITY_STATUS_OF_OK),
        ];
    }

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    /**
     * ActivityRepository constructor.
     * @param CouponRepositoryContract $couponRepo
     */
    public function __construct(CouponRepositoryContract $couponRepo)
    {
        $this->couponRepo = $couponRepo;
    }

    public function createActivity($data)
    {
        $activity_data = $this->filterData($data);
        $activity_data['activity_no'] = uniqid('activity_');

        $activity = Activity::create($activity_data);

        return $activity;
    }

    public function updateActivity($data, $activity_id)
    {
        $activity = $this->get($activity_id);
        $activity->fill($this->filterData($data));
        $activity->save();

        return $activity;
    }

    public function setAsUnActive($activity_id)
    {
        $activity = $this->get($activity_id);
        $activity->status = ActivityProtocol::ACTIVITY_STATUS_OF_DRAFT;
        $activity->save();
        return $activity;
    }

    public function setAsActive($activity_id)
    {
        $activity = $this->get($activity_id);
        $activity->status = ActivityProtocol::ACTIVITY_STATUS_OF_OK;
        $activity->save();
        return $activity;
    }

    public function get($activity_id, $with_detail = false)
    {
        if ($activity_id instanceof Activity) {
            $activity = $activity_id;
        } else if (is_numeric($activity_id)) {
            $activity = Activity::query()->findOrFail($activity_id);
        } else {
            $activity = Activity::query()->where('activity_no', $activity_id)->firstOrFail();
        }

        if ($with_detail) {
            $activity->coupon_list = $this->couponRepo->getCouponsById($activity->coupons);
        }

        return $activity;
    }

    /**
     * @param null $status
     * @param null $start_time
     * @param null $end_time
     * @param int $per_page
     * @return Paginator
     */
    public function getAllPaginated($status = null, $start_time = null, $end_time = null, $per_page = ActivityProtocol::PER_PAGE)
    {
        return $this->query($status, $start_time, $end_time, $per_page);
    }

    public function getAll($status = null, $start_time = null, $end_time = null)
    {
        return $this->query($status, $start_time, $end_time);
    }

    protected function query($status = null, $start_time = null, $end_time = null, $per_page = null)
    {
        $query = Activity::query();

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($start_time)) {
            $query->where('end_time', '>', $start_time);
        }

        if (!is_null($end_time)) {
            $query->where('start_time', '<=', $end_time);
        }

        $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');

        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }


}
