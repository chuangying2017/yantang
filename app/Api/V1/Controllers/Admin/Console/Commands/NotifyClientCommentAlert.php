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
                    ->where('staff_id','>',0)
                    ->orWhereBetween('start_time',[Carbon::today()->addDays(-3),Carbon::today()])
                    ->orWhereDate('end_time','=',Carbon::yesterday()->toDateString())
                    ->orWhere('restart_time','!=',null);
            })
            ->where('comment_identify',PreorderProtocol::COMMENT_IDENTIFY)
            ->chunk('100',function ($collectData){
                file_put_contents('sureInFollow.txt',$collectData);
                foreach ($collectData as $collectDatum){
                        file_put_contents('Skus_Collect.txt','---'.$collectDatum->skus."\r\n",FILE_APPEND);

                        //A single delivery
                        try{

                            foreach ($collectDatum->comments as $comment){

                                $total = array_multi_sort($collectDatum->skus->toArray(),['total'=>'desc']);
                                if(empty($total[0]['remain']) && empty($comment->comment_type) && $collectDatum['status'] == PreorderProtocol::ORDER_STATUS_OF_DONE)
                                {

                                    $comment->comment_type = CommentProtocol::COMMENT_STATUS_IS_NOT_USES;//待评价
                                    $comment->save();

                                    file_put_contents('Completed.txt',$comment.'--'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND);

                                    $collectDatum->comment_identify = PreorderProtocol::COMMENT_IDENTIFY_THREE;//表示最后一次模板消息推送
                                    $collectDatum->save();
                                    NotifyProtocol::notify($collectDatum['user_id'],NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
                                    continue;
                                }
                                file_put_contents('collect_Datum.txt',$collectDatum.'---'.date('Y-m-d H:i:s',time())."\r\n",FILE_APPEND);
                                //A single delivery
                                file_put_contents('commentType.txt',$comment->comment_type.' --'.date('Y-m-d H:i:s',time())."---\r\n",FILE_APPEND);
                                //Multiple delivery

                                $rest = empty($collectDatum['restart_time']) ? true : Carbon::parse($collectDatum['restart_time'])->timestamp <= Carbon::today()->timestamp;

                                if(empty($comment->comment_type) && $total[0]['total'] == ($total[0]['per_day'] * 3 + $total[0]['remain']) && $rest)
                                {
                                    $comment->comment_type = CommentProtocol::COMMENT_STATUS_IS_NOT_USES;//待评价
                                    $comment->save();
                                    file_put_contents('manyNum.txt',$comment.'--'.date('Y-m-d H:i:s')."\r\n",FILE_APPEND);
                                    NotifyProtocol::notify($collectDatum['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
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
