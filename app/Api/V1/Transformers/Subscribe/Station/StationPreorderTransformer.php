<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Station;
use App\Services\Subscribe\PreorderProtocol;

class StationPreorderTransformer extends TransformerAbstract
{

    public function transform(Station $station)
    {
        $this->setDefaultIncludes(['preorder']);
        $menus = PreorderProtocol::stationPreorderMenus();

        $data = [
            'id' => (int)$station->id,
            'menus' => $menus,
        ];

        return $data;
    }

}
