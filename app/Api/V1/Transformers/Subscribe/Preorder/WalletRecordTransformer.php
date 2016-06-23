<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Client\Account\WalletRecord;

class WalletRecordTransformer extends TransformerAbstract
{

    public function transform(WalletRecord $walletRecord)
    {

        $data = [
            'id' => $walletRecord->id,
            'user_id' => $walletRecord->user_id,
            'amount' => $walletRecord->amount,
            'income' => $walletRecord->income,
            'resource_type' => $walletRecord->resource_type,
            'resource_id' => $walletRecord->resource_id,
            'type' => $walletRecord->type,
            'status' => $walletRecord->status,
            'created_at' => $walletRecord->created_at,
            'updated_at' => $walletRecord->updated_at,
        ];

        return $data;
    }


}
