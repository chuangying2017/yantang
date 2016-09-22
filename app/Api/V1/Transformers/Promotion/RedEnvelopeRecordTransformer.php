<?php namespace App\API\V1\Transformers\Promotion;

use App\Api\V1\Transformers\Traits\SetInclude;
use App\Models\RedEnvelope\RedEnvelopeRecord;
use League\Fractal\TransformerAbstract;

class RedEnvelopeRecordTransformer extends TransformerAbstract {

    use SetInclude;

    protected $defaultIncludes = ['current_receiver'];
    protected $availableIncludes = ['current_receiver', 'rule', 'receivers'];

    public function transform(RedEnvelopeRecord $record)
    {
        $this->setInclude($record);

        $data = [
            'id' => $record['id'],
            'total' => $record['total'],
            'dispatch' => $record['dispatch'],
            'start_time' => $record['start_time'],
            'end_time' => $record['end_time'],
            'status' => $record['status'],
        ];

        return $data;
    }

    public function includeRule(RedEnvelopeRecord $record)
    {
        return $this->item($record->rule, new RedEnvelopeRuleTransformer(), true);
    }

    public function includeReceivers(RedEnvelopeRecord $record)
    {
        return $this->collection($record->receivers, new RedEnvelopeReceiveTransformer(), true);
    }

    public function includeCurrentReceiver(RedEnvelopeRecord $record)
    {
        $current_user_id = access()->id();
        foreach ($record->receivers as $receiver) {
            if ($receiver['user_id'] == $current_user_id) {
                return $this->item($receiver, new RedEnvelopeReceiveTransformer(), true);
            }
        }

        return null;
    }
    
}
