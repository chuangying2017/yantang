<?php namespace App\Services\Order\Generator;

use App\Models\Promotion\Campaign;
use App\Repositories\Promotion\Campaign\CampaignRepositoryContract;
use App\Services\Promotion\CampaignService;

class UseCampaign extends GenerateHandlerAbstract {


    /**
     * @var CampaignRepositoryContract
     */
    private $campaignRepo;


    /**
     * UseCoupon constructor.
     * @param CampaignService $campaignService
     */
    public function __construct(CampaignRepositoryContract $campaignRepo)
    {
        $this->campaignRepo = $campaignRepo;
    }

    public function handle(TempOrder $temp_order)
    {
        $campaign_id = $temp_order->getRequestPromotion();
        $campaign = $this->campaignRepo->get($campaign_id, false);
        if (count($campaign['rules']) == 1) {
            foreach ($campaign['rules'] as $rule) {
                $order_promotion = [
                    'id' => $rule['id'],
                    'promotion_id' => $campaign_id,
                    'promotion_type' => Campaign::class,
                ];
                $temp_order->setPromotion($order_promotion);
            }
        } else {
            throw new \Exception('请求参数错误');
        }

        return $this->next($temp_order);
    }
}
