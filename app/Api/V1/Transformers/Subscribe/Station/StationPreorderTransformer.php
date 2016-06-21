<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Station;
use App\Services\Subscribe\PreorderProtocol;

class StationPreorderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['search_type'];

    public function transform(Station $station)
    {
        $this->setInclude();
        $data = [
            'id' => (int)$station->id,
            'user' => $station->preorder->user->name,
            'status' => $station->preorder->status,
            'charge_status' => $station->preorder->charge_status,
            'status_name' => PreorderProtocol::StatusName($station->preorder->status, $station->preorder->charge_status),
            'preorder_id' => $station->preorder->id,
        ];

        return $data;
    }

    public function includeSearchType()
    {
        $menus = PreorderProtocol::stationPreorderStatus();

        return $this->array(['menus' => $menus]);
    }

}
