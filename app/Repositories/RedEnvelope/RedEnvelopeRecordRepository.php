<?php namespace App\Repositories\RedEnvelope;

use App\Models\RedEnvelope\RedEnvelopeRecord;
use Carbon\Carbon;

class RedEnvelopeRecordRepository {

    /**
     * @var RedEnvelopeRulesRepository
     */
    private $ruleRepo;

    /**
     * RedEnvelopeRecordRepository constructor.
     * @param RedEnvelopeRulesRepository $ruleRepo
     */
    public function __construct(RedEnvelopeRulesRepository $ruleRepo)
    {
        $this->ruleRepo = $ruleRepo;
    }

    public function createRecord($rule_id, $user_id, $resource_type, $resource_id)
    {
        $rule = $this->ruleRepo->get($rule_id);

        if ($rule['total'] > 0 && $rule['total'] <= $rule['dispatch']) {
            return false;
        }

        if ($rule['start_time'] > Carbon::now() || $rule['end_time'] < Carbon::now()) {
            return false;
        }

        $exist_record = $this->getByResource($resource_type, $resource_id);
        if ($exist_record) {
            return $exist_record;
        }

        $record = RedEnvelopeRecord::create([
            'user_id' => $user_id,
            'rule_id' => $rule['id'],
            'start_time' => $rule['start_time'],
            'end_time' => $this->calEndTime($rule['effect_days'], $rule['end_time']),
            'total' => $rule['quantity'],
            'dispatch' => 0,
            'resource_type' => $resource_type,
            'resource_id' => $resource_id,
            'status' => RedEnvelopeProtocol::RECORD_STATUS_OF_OK,
        ]);

        return $record;
    }

    public function getByResource($resource_type = RedEnvelopeProtocol::TYPE_OF_SUBSCRIBE_ORDER, $resource_id)
    {
        return RedEnvelopeRecord::query()->where('resource_type', $resource_type)
            ->where('resource_id', $resource_id)->first();
    }

    public function get($record_id, $with_detail = false)
    {
        $record = RedEnvelopeRecord::query()->find($record_id);

        if ($with_detail) {
            $record->load('rule', 'receivers');
        }

        return $record;
    }

    public function incrementDispatch($record_id, $count = 1)
    {
        $record = $this->get($record_id);
        if ($record->total > $record->dispatch + $count) {
            throw new \Exception('红包已领完');
        }
        $record->dispatch += $count;
        $record->save();

        return $record;
    }

    private function calEndTime($effect_days, $end_time)
    {
        $effect_end_time = Carbon::parse($end_time)->addDay($effect_days);

        return $effect_end_time < $end_time ? $effect_end_time : $end_time;
    }

    public function updateAsCancel($record_id)
    {
        $record = $this->get($record_id);
        $record->status = RedEnvelopeProtocol::RECORD_STATUS_OF_CANCEL;
        $record->save();

        return $record;
    }


}
