<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Station;
use App\Services\Subscribe\PreorderProtocol;

class StationPreorderTransformer extends TransformerAbstract
{

    public function transform(Station $station)
    {
        $includes = ['search_type'];
        $this->setDefaultIncludes($includes);
        $data = [
            'id' => (int)$station->id,
            'user' => $station->preorder->user->name,
            'status' => $station->preorder->status,
            'status_name' => PreorderProtocol::status($station->preorder->status),
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
