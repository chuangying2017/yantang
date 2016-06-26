<?php namespace App\Services\Order\Generator;

use App\Repositories\Promotion\Campaign\CampaignRepositoryContract;


class GetSpecialCampaign extends GenerateHandlerAbstract {

    /**
     * @var CampaignRepositoryContract
     */
    private $campaignRepo;

    /**
     * GetSpecialCampaign constructor.
     * @param CampaignRepositoryContract $campaignRepo
     */
    public function __construct(CampaignRepositoryContract $campaignRepo)
    {
        $this->campaignRepo = $campaignRepo;
    }

    public function handle(TempOrder $temp_order)
    {
        $campaign = $this->campaignRepo->get($temp_order->getSpecialCampaign(), false);

        $temp_order->setSpecialCampaign(['campaign_id' => $campaign['id'], 'campaign_name' => $campaign['name']]);
        return $this->next($temp_order);
    }
}
