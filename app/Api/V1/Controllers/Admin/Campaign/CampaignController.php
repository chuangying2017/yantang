<?php

namespace App\Api\V1\Controllers\Admin\Campaign;

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
        $campaigns = $this->campaignRepo->getAllPaginated(false);

        return $this->response->paginator($campaigns, new CampaignTransformer());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campaign = $this->campaignRepo->create($request->all());

        return $this->response->created()->setContent(['data' => $campaign->toArray()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = $this->campaignRepo->get($id);

        return $this->response->item($campaign, new CampaignTransformer());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campaign = $this->campaignRepo->update($id, $request->all());
        return $this->response->item($campaign, new CampaignTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->campaignRepo->delete($id);

        return $this->response->noContent();
    }
}
