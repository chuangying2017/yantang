<?php

namespace App\Api\V1\Controllers\Campaign;

use App\Api\V1\Transformers\Campaign\CampaignTransformer;
use App\Repositories\Promotion\Campaign\CampaignRepositoryContract;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CampaignController extends Controller {

    /**
     * @var CampaignRepositoryContract
     */
    private $campaignRepo;

    /**
     * CampaignController constructor.
     * @param CampaignRepositoryContract $campaignRepo
     */
    public function __construct(CampaignRepositoryContract $campaignRepo)
    {
        $this->campaignRepo = $campaignRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = $this->campaignRepo->getAll();

        return $this->response->collection($campaigns, new CampaignTransformer());
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = $this->campaignRepo->get($id, true);

        return $this->response->item($campaign, new CampaignTransformer());
    }


}
