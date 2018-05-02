<?php namespace App\Services\Order\Generator;

use App\Repositories\Auth\User\UserContract;
use App\Services\Promotion\CampaignService;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class CheckCampaign extends GenerateHandlerAbstract {

    /**
     * @var CampaignService
     */
    private $campaignService;

    /**
     * @var PromotionAbleUserContract
     */
    private $userContract;

    /**
     * CheckCampaign constructor.
     * @param CampaignService $campaignService
     * @param PromotionAbleUserContract $userContract
     */
    public function __construct(CampaignService $campaignService, PromotionAbleUserContract $userContract)
    {
        $this->campaignService = $campaignService;
        $this->userContract = $userContract;
    }

    public function handle(TempOrder $temp_order)
    {
        $this->userContract->setUser($temp_order->getUser());

        $this->campaignService
            ->setUser($this->userContract)
            ->setItems($temp_order)
            ->autoUsing();

        $temp_order->setCampaigns($this->campaignService->getRules());

        return $this->next($temp_order);
    }

}
