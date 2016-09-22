<?php namespace App\Repositories\RedEnvelope;

use App\Models\RedEnvelope\RedEnvelopeReceive;
use App\Repositories\Client\ClientRepositoryContract;

class RedEnvelopeReceiveRepository {

    public function createReceiver($record_id, $user_id, $ticket_id, $coupon_id, $coupon_content)
    {
//        $record = $this->getByUser($record_id, $user_id);
//        if ($record) {
//            return $record;
//        }
        $client = app()->make(ClientRepositoryContract::class)->showClient($user_id);

        return RedEnvelopeReceive::create([
            'user_id' => $user_id,
            'record_id' => $record_id,
            'nickname' => $client['nickname'] ?: '燕塘优优鲜达用户',
            'avatar' => $client['avatar'] ?: '',
            'coupon_id' => $coupon_id,
            'ticket_id' => $ticket_id,
            'content' => $coupon_content,
        ]);
    }

    public function getByUser($record_id, $user_id)
    {
        return RedEnvelopeReceive::query()->where('record_id', $record_id)->where('user_id', $user_id)->first();
    }


}
