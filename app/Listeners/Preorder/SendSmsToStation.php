<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\AssignIsCreate;
use App\Repositories\Preorder\PreorderRepositoryContract;
use App\Services\Preorder\PreorderProtocol;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Toplan\PhpSms\Sms;

class SendSmsToStation {


    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;

    /**
     * Create the event listener.
     *
     * @param PreorderRepositoryContract $preorderRepo
     */
    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  AssignIsCreate $event
     * @return void
     */
    public function handle(AssignIsCreate $event)
    {
        $assign = $event->assign;

        $preorder = $this->preorderRepo->get($assign['preorder_id']);

        if ($preorder['status'] !== PreorderProtocol::ORDER_STATUS_OF_UNPAID) {

            $preorder->load('station');

            $phone = $preorder['station']['phone'];
            
            $result = Sms::make()->to($phone)->content('您有新的订奶订单,请查看【燕塘优鲜达】')->send();
        }


    }


}
