<?php namespace App\Repositories\Promotion;

use App\Models\Promotion\PromotionAbstract;

interface TicketRepositoryContract {

    public function createTicket($user_id = null, PromotionAbstract $promotion, $generate_no = false);

    public function createLogTicket($user_id, $promotion_id, $promotion_type, $rule_id);

    public function deleteTicket($ticket_id);

    public function updateAsUsed($ticket_id, $rule_id = 0);

    public function updateAsOk($ticket_id);

    public function updateAsExpire($ticket_id);

    public function rollback($ticket_id);

    public function updateAsCancel($ticket_id);

    public function getTicket($ticket_id, $with_promotion = true);

    public function getCouponTicketsOfUser($user_id, $status, $with_promotion = true);
    
    public function getCouponTicketsOfUserPaginated($user_id, $status, $with_promotion = true);

    public static function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null);

}
