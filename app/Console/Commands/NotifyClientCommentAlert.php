<?php

namespace App\Console\Commands;

use App\Models\Subscribe\Preorder;
use App\Services\Notify\NotifyProtocol;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyClientCommentAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:client-comment-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       // file_put_contents('sureInFollow.txt','In Follow---|'."\r\n",FILE_APPEND);
        // dispose
        Preorder::query()
            ->where(function($query){
                $query->whereIn('status',[PreorderProtocol::ORDER_STATUS_OF_SHIPPING,PreorderProtocol::ORDER_STATUS_OF_DONE])
                    ->whereBetween('start_time',[Carbon::now()->addDays(-3),Carbon::now()]);
            })
            ->where('comment_identify',PreorderProtocol::COMMENT_IDENTIFY)
            ->chunk('100',function ($collectData){
              //  file_put_contents('sureInFollow.txt','collectData---'.$collectData,FILE_APPEND);
                foreach ($collectData as $collectDatum){
                       // file_put_contents('testCollect.txt','---'.$collectDatum->comments);
                         /* && $collectDatum['start_time'] == Carbon::yesterday()->toDateString() && date('H',time()) == '8'*/
                        //A single delivery
                        if($collectDatum->comments[0]['content_type'] == 0 && $collectDatum['start_time'] == $collectDatum['end_time'] && $collectDatum['start_time'] == Carbon::yesterday()->toDateString()){
                           // file_put_contents('testCollectaa.txt','qqqq111');
                            $collectDatum->comments[0]->comment_type = 'ToBeUsed';//待评价
                            $collectDatum->comments[0]->save();
                            NotifyProtocol::notify($collectDatum['user_id'],NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
                        }
                        //Multiple delivery
                        if($collectDatum->comments[0]['content_type'] == 0 && $collectDatum['start_time'] == Carbon::now()->addDays(-3)->toDateString()){
                            $collectDatum->comments[0]->comment_type = 'ToBeUsed';//待评价
                            $collectDatum->comments[0]->save();
                            NotifyProtocol::notify($collectDatum['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
                        }

                }
            });
    }
}
