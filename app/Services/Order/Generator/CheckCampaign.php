<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\CampaignService;

class CheckCampaign extends GenerateHandlerAbstract {

    /**
     * @var CampaignService
     */
    private $campaignService;

    /**
     * CheckCampaign constructor.
     * @param CampaignService $campaignService
     */
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function handle(TempOrder $temp_order)
    {
        $this->campaignService->autoUsing($temp_order);
        return $this->next($temp_order);
    }
}
