<?php namespace App\Services\Order\Generator;
class CheckCampaign extends GenerateHandlerAbstract {

    public function handle(TempOrder $temp_order)
    {
        #todo 检查可参加的活动

        return $this->next($temp_order);
    }
}
