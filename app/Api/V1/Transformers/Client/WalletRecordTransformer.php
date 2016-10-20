<?php namespace App\Api\V1\Transformers\Client;

use App\Models\Client\Account\WalletRecord;
use App\Services\Client\Account\AccountProtocol;
use League\Fractal\TransformerAbstract;

class WalletRecordTransformer extends TransformerAbstract {

    public function transform(WalletRecord $record)
    {
        return [
            'id' => $record['id'],
            'user' => ['id' => $record['user_id']],
            'amount' => $record['amount'],
            'income' => $record['income'],
            'status' => $record['status'],
            'type' => $record['type'],
            'created_at' => $record['created_at'],
            'type_name' => AccountProtocol::accountRecordType($record['type']),
        ];
    }

}
