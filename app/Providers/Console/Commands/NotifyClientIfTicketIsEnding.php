<?php

namespace App\Console\Commands;

use App\Models\Promotion\Ticket;
use App\Services\Notify\NotifyProtocol;
use App\Services\Promotion\PromotionProtocol;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyClientIfTicketIsEnding extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:ticket-ending {days=3}';

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
        $start_time = Carbon::today()->addDays($this->argument('days'));
        $end_time = $start_time->copy()->addDay();
        Ticket::query()
            ->where('status', PromotionProtocol::STATUS_OF_TICKET_OK)
            ->whereBetween('end_time', [$start_time, $end_time])
            ->chunk(100, function ($tickets) {
                foreach ($tickets as $ticket) {
                    NotifyProtocol::notify($ticket['user_id'], NotifyProtocol::NOTIFY_ACTION_CLIENT_TICKET_IS_ENDING, null, $ticket);
//                    echo 'notify ' . $ticket['user_id'] . ' ticket ' . $ticket['id'] . "\n";
                }
            });
    }
}
