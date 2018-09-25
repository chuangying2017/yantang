<?php namespace App\Api\V1\Transformers\Admin\Client;
use App\Models\Client\Client;
use App\Repositories\Member\ReposFile\MemberLimitRepository;
use League\Fractal\TransformerAbstract;

class ClientUserTransformer extends TransformerAbstract{


    public function transform(Client $client)
    {
        $data = $client->toArray();

        $data['new_member'] = $this->new_member($data['user_id']);

        return $data;
    }

    public function new_member($userId)
    {
        $member = new MemberLimitRepository();

        return $member->new_member($userId);
    }
}
