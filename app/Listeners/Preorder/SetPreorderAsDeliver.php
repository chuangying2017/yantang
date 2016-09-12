<?php

namespace App\Listeners\Preorder;

use App\Events\Preorder\PreorderDeliverStart;
use App\Repositories\Preorder\PreorderRepositoryContract;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetPreorderAsDeliver {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PreorderRepositoryContract $preorderRepo)
    {
        $this->preorderRepo = $preorderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  PreorderDeliverStart $event
     * @return void
     */
    public function handle(PreorderDeliverStart $event)
    {
        $preorder = $event->preorder;
        $this->preorderRepo->updatePreorderAsDeliver($preorder);
    }

    /**
     * @var PreorderRepositoryContract
     */
    private $preorderRepo;
}
