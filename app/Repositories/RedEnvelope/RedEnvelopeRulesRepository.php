<?php namespace App\Repositories\RedEnvelope;

use App\Models\RedEnvelope\RedEnvelopeRule;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use Illuminate\Pagination\Paginator;

class RedEnvelopeRulesRepository {

    /**
     * @param $data
     * @return array
     */
    protected function filterData($data)
    {
        return [
            'name' => $data['name'],
            'desc' => $data['desc'],
            'type' => $data['type'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'coupons' => $data['coupons'],
            'content' => $data['content'],
            'quantity' => $data['quantity'],
            'effect_days' => $data['effect_days'],
            'total' => array_get($data, 'total', 0),
            'status' => array_get($data, 'status', RedEnvelopeProtocol::RULE_STATUS_OF_OK)
        ];
    }

    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;

    /**
     * RedEnvelopeRulesRepository constructor.
     * @param CouponRepositoryContract $couponRepo
     */
    public function __construct(CouponRepositoryContract $couponRepo)
    {
        $this->couponRepo = $couponRepo;
    }

    public function updateRule($data, $rule_id = null)
    {
        if (!is_null($rule_id)) {
            $rule = RedEnvelopeRule::query()->findOrFail($rule_id);
            $rule->fill($this->filterData($data));
            $rule->save();
        } else {
            $rule = RedEnvelopeRule::query()->updateOrCreate(['type' => $data['type']], $this->filterData($data));
        }

        return $rule;
    }

    public function setAsUnActive($rule_id)
    {
        $rule = $this->get($rule_id);
        $rule->status = RedEnvelopeProtocol::RULE_STATUS_OF_DRAFT;
        $rule->save();
        return $rule;
    }

    public function setAsActive($rule_id)
    {
        $rule = $this->get($rule_id);
        $rule->status = RedEnvelopeProtocol::RULE_STATUS_OF_OK;
        $rule->save();
        return $rule;
    }

    public function get($rule_id, $with_detail = false)
    {
        if ($rule_id instanceof RedEnvelopeRule) {
            $rule = $rule_id;
        } else if (RedEnvelopeProtocol::type($rule_id)) {
            $rule = RedEnvelopeRule::query()->where('status', RedEnvelopeProtocol::RULE_STATUS_OF_OK)->where('type', $rule_id)->first();
        } else {
            $rule = RedEnvelopeRule::query()->findOrFail($rule_id);
        }

        if ($with_detail) {
            $rule->coupon_list = $this->couponRepo->getCouponsById($rule->coupons);
        }

        return $rule;
    }

    /**
     * @param null $status
     * @param null $start_time
     * @param null $end_time
     * @param int $per_page
     * @return Paginator
     */
    public function getAllPaginated($status = null, $start_time = null, $end_time = null, $per_page = RedEnvelopeProtocol::PER_PAGE)
    {
        return $this->query($status, $start_time, $end_time, $per_page);
    }

    public function getAll($status = null, $start_time = null, $end_time = null)
    {
        return $this->query($status, $start_time, $end_time);
    }

    protected function query($status = null, $start_time = null, $end_time = null, $per_page = null)
    {
        $query = RedEnvelopeRule::query();

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($start_time)) {
            $query->where('end_time', '>', $start_time);
        }

        if (!is_null($end_time)) {
            $query->where('start_time', '<=', $end_time);
        }

        $query->orderBy('created_at', 'desc');


        if (!is_null($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
    }


}
