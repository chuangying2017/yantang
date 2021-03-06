<?php namespace App\Repositories\Promotion;

use App\Models\Promotion\PromotionAbstract;
use App\Services\Promotion\PromotionProtocol;

interface TicketRepositoryContract {

    public function createTicket($user_id = null, PromotionAbstract $promotion, $generate_no = false, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_USER, $source_id = 0);

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

    public static function getTicketBySource($source_type, $source_id = null);

}
