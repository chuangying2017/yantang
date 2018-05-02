<?php 
namespace App\Services\Promotion;
use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Repositories\Promotion\TicketRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;
use App\Repositories\Promotion\Giftcard\EloquentGiftcardRepository;
use Carbon\Carbon;
use DB;

class CouponService extends PromotionServiceAbstract implements PromotionDispatcher {


    /**
     * @var TicketRepositoryContract
     */
    private $ticketRepo;
    private $giftcardRepo;

    /**
     * CouponService constructor.
     * @param CouponRepositoryContract $couponRepo
     * @param RuleServiceContract $ruleService
     * @param TicketRepositoryContract $ticketRepo
     */
    public function __construct(CouponRepositoryContract $couponRepo, RuleServiceContract $ruleService, TicketRepositoryContract $ticketRepo, EloquentGiftcardRepository $giftcardRepo)
    {
        parent::__construct($couponRepo, $ruleService);
        $this->ticketRepo = $ticketRepo;
        $this->giftcardRepo = $giftcardRepo;
    }

    public function dispatch(PromotionAbleUserContract $user, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_USER, $source_id = 0)
    {
        $promotion = $this->promotionRepo->getPromotionWithDecodeRules($promotion_id);
		
		
		
		if($promotion['new_limit']==1){
			 $if_hadorder = DB::select("SELECT * from orders where  user_id='".$user->getUserId()."' order by id desc limit 0,1");
			  if(!empty($if_hadorder)){
					$this->setErrorMessage('此优惠券新用户才可以领取哦！'); 
					 return false;
				}
		}
		
		
		//
		//echo "error";
		//return false;
		//file_put_contents("promotion.txt",date("Y-m-d H:i:s")."\nlog=".json_encode($promotion['counter']["dispatch"])."\n\n");
		if($promotion['counter']["dispatch"]>=$promotion['counter']["total"]){
			$this->setErrorMessage('对不起，已领完了！');
			//file_put_contents("promotion2.txt",date("Y-m-d H:i:s")."\nlog=aaaaa\n\n");
            return false;
		}
		
        //非有效期内
        if ($promotion['start_time'] > Carbon::now() || $promotion['end_time'] < Carbon::now() ) {
            $this->setErrorMessage('优惠券不在有效期内');
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
		//file_put_contents("CouponService.txt",date("Y-m-d H:i:s")."\nlog=".json_encode($this->ticketRepo->createTicket($user->getUserId(), $promotion, true, $source_type, $source_id))."\n\n");
        return $this->ticketRepo->createTicket($user->getUserId(), $promotion, true, $source_type, $source_id);
    }


    public function dispatchWithoutCheck($user_id, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_ADMIN, $source_id = 0)
    {
        $promotion = $this->promotionRepo->getPromotionWithDecodeRules($promotion_id);

        return $this->ticketRepo->createTicket($user_id, $promotion, true, $source_type, $source_id);
    }

    public function dispatchGiftcard(  $user_id, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_ADMIN, $source_id = 0)
    {
        $giftcard = $this->giftcardRepo->get($promotion_id);

        return $this->ticketRepo->createTicket($user_id, $giftcard, true, $source_type, $source_id);
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
