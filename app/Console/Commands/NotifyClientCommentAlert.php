<?php

namespace App\Console\Commands;

use App\Models\Subscribe\Preorder;
use App\Repositories\Comment\CommentProtocol;
use App\Services\Notify\NotifyProtocol;
use App\Services\Preorder\PreorderProtocol;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        // dispose
        Preorder::query()
            ->where(function($query){
                $query->whereIn('status',[PreorderProtocol::ORDER_STATUS_OF_SHIPPING,PreorderProtocol::ORDER_STATUS_OF_DONE])
                    ->whereBetween('start_time',[Carbon::today()->addDays(-3),Carbon::today()])->orWhereDate('end_time','=',Carbon::yesterday());
            })
            ->where('comment_identify',PreorderProtocol::COMMENT_IDENTIFY)
            ->chunk('100',function ($collectData){
                //file_put_contents('sureInFollow.txt',$collectData."\r\n",FILE_APPEND);
                foreach ($collectData as $collectDatum){
                        file_put_contents('Skus_Collect.txt','---'.$collectDatum->skus."\r\n",FILE_APPEND);
                       // file_put_contents('testCollect.txt','---'.$collectDatum->comments."\r\n",FILE_APPEND);
                        //A single delivery
                        try{
                            \Cache::put('collectData',$collectDatum,'300');
                            foreach ($collectDatum->comments as $comment){

                             

                                file_put_contents('collect_Datum.txt',$collectDatum.'---'.date('Y-m-d H:i:s',time())."\r\n",FILE_APPEND);
                                //A single delivery
                                file_put_contents('commentType.txt',$comment->comment_type.' --'.date('Y-m-d H:i:s',time())."---\r\n",FILE_APPEND);
                                $parse_startTime = Carbon::parse($collectDatum['start_time'])->timestamp;
                                $parse_endTime = Carbon::parse($collectDatum['end_time'])->timestamp;
                                if(empty($comment->comment_type) && $parse_startTime == $parse_endTime && $parse_startTime == Carbon::yesterday()->timestamp)
                                {
                                    $comment->comment_type = CommentProtocol::COMMENT_STATUS_IS_NOT_USES;//待评价
                                    $comment->save();
                                    file_put_contents('leshang.txt',$collectDatum.'---'.date('Y-m-d H:i:s',time()).'--|'."\r\n",FILE_APPEND);
                                    NotifyProtocol::notify($collectDatum['user_id'],NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
                                    continue;
                                }

                                //Multiple delivery

                                if(empty($comment->comment_type) && $parse_startTime == Carbon::today()->addDays(-3)->timestamp && $parse_startTime < $parse_endTime)
                                {
                                    $comment->comment_type = CommentProtocol::COMMENT_STATUS_IS_NOT_USES;//待评价
                                    $comment->save();
                                    file_put_contents('manyNum.txt',$comment.'--'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND);
                                    NotifyProtocol::notify($collectDatum['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
                                    continue;
                                }

                                if(empty($comment->comment_type) && $collectDatum['status'] == PreorderProtocol::ORDER_STATUS_OF_DONE && $parse_startTime < $parse_endTime && $parse_endTime == Carbon::yesterday()->timestamp)
                                {
                                    $comment->comment_type = CommentProtocol::COMMENT_STATUS_IS_NOT_USES;
                                    $comment->save();
                                    file_put_contents('endDayAlert.txt',$comment.'---'.date('Y-m-d H:i:s',time())."\r\n",FILE_APPEND);
                                    NotifyProtocol::notify($collectDatum['user_id'],NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
                                    continue;
                                }
                            }
                        }catch (\Exception $exception) {
                            Log::erro($exception->getMessage());
                        }
                }
            });
    }
}
