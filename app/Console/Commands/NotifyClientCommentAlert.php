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
       // file_put_contents('sureInFollow.txt','In Follow---|'."\r\n",FILE_APPEND);
        // dispose
        Preorder::query()
            ->where(function($query){
                $query->whereIn('status',[PreorderProtocol::ORDER_STATUS_OF_SHIPPING,PreorderProtocol::ORDER_STATUS_OF_DONE])
                    ->whereBetween('start_time',[Carbon::now()->addDays(-3),Carbon::now()]);
            })
            ->where('comment_identify',PreorderProtocol::COMMENT_IDENTIFY)
            ->chunk('100',function ($collectData){
                file_put_contents('sureInFollow.txt',$collectData."\r\n",FILE_APPEND);
                foreach ($collectData as $collectDatum){
                        file_put_contents('testCollect.txt','---'.$collectDatum->comments."\r\n",FILE_APPEND);
                         /* && $collectDatum['start_time'] == Carbon::yesterday()->toDateString() && date('H',time()) == '8'
                          && $collectDatum['start_time'] == Carbon::yesterday()->toDateString()
                         */
                        //A single delivery
                        try{
                            \Cache::put('collectData',$collectDatum);
                            foreach ($collectDatum->comments as $comment){

                                //A single delivery
                                file_put_contents('commentType.txt',$comment->comment_type.date('Y-m-d H:i:s',time())."---\r\n",FILE_APPEND);
                                if(empty($comment->comment_type) && $collectDatum['start_time'] == $collectDatum['end_time']){
                                    $comment->comment_type = CommentProtocol::COMMENT_STATUS_IS_NOT_USES;//待评价
                                    $comment->save();
                                    file_put_contents('leshang.txt',$collectDatum.'---'.date('Y-m-d H:i:s',time()).'--|'."\r\n",FILE_APPEND);
                                    NotifyProtocol::notify($collectDatum['user_id'],NotifyProtocol::NOTIFY_ACTION_CLIENT_COMMENT_IS_ALERT,null,$collectDatum);
                                    continue;
                                }

                                //Multiple delivery
                                $parse = Carbon::parse($collectDatum['start_time'])->timestamp;
                                if(empty($comment->comment_type) === (int)0 && $parse == Carbon::now()->addDays(-3)->timestamp && $parse < Carbon::parse($collectDatum['end_time'])->timestamp){
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
