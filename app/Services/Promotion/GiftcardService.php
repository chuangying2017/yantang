<?php namespace App\Services\Promotion;


use App\Repositories\Promotion\Giftcard\GiftcardRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;
use App\Repositories\Promotion\Giftcard\EloquentGiftcardRepository;
use Carbon\Carbon;

class GiftcardService extends PromotionServiceAbstract implements PromotionDispatcher {


    /**
     * @var GiftcardRepositoryContract
     */
    private $giftcardRepo;

    /**
     * GiftcardService constructor.
     * @param GiftcardRepositoryContract $giftcardRepo
     * @param RuleServiceContract $ruleService
     * @param GiftcardRepositoryContract $giftcardRepo
     */
    public function __construct(GiftcardRepositoryContract $giftcardRepo, RuleServiceContract $ruleService)
    {
        parent::__construct($giftcardRepo, $ruleService);
        $this->ticketRepo = $giftcardRepo;
        $this->giftcardRepo = $giftcardRepo;
    }

    public function dispatch(PromotionAbleUserContract $user, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_USER, $source_id = 0)
    {
        $promotion = $this->promotionRepo->getPromotionWithDecodeRules($promotion_id);

        //非有效期内
        if ($promotion['start_time'] > Carbon::now() && $promotion['end_time'] < Carbon::now()) {
            $this->setErrorMessage('礼品卡不在有效期内');
            return false;
        }

        if (empty($promotion['rules'])) {
            $this->setErrorMessage('来晚了');
            return false;
        }

        $this->ruleService->setUser($user)->setRules($promotion['rules']);

        $this->ruleService->filterQualify();

        //无资格领取
        if (empty($this->ruleService->getRules())) {
            $this->setErrorMessage('已经领取或不符合领取资格,领取失败');
            return false;
        }

        return $this->giftcardRepo->createTicket($user->getUserId(), $promotion, true, $source_type, $source_id);
    }


    public function dispatchWithoutCheck($user_id, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_ADMIN, $source_id = 0)
    {
        $promotion = $this->promotionRepo->getPromotionWithDecodeRules($promotion_id);

        return $this->giftcardRepo->createTicket($user_id, $promotion, true, $source_type, $source_id);
    }


    public function cancelByResource($resource_type, $resource_id)
    {
        $tickets = $this->ticketRepo->getTicketBySource($resource_type, $resource_id);
        if (count($tickets)) {
            foreach ($tickets as $ticket) {
                $this->ticketRepo->updateAsCancel($ticket);
            }
        }
    }
}
