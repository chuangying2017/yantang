<?php

namespace App\Services\Agent\Listeners;

use App\Services\Agent\Event\NewAgent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotifyToAgent {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewAgent $event
     * @return void
     */
    public function handle(NewAgent $event)
    {
        try {
            $info = $event->agent_info;
            $phone = $info['phone'];
            $content = \Config::get('laravel-sms.notifyAgentApprove');
            app('PhpSms')->make()->to($phone)->content($content)->send();
        } catch (\Exception $e) {
            \Log::error('代理短信发送失败');
            \Log::error($e);
        }
    }
}
