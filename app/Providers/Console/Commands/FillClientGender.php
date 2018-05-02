<?php

namespace App\Console\Commands;

use App\Models\Client\Client;
use Illuminate\Console\Command;

class FillClientGender extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:gender';

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
        echo "start \n";
        Client::query()->chunk(1000, function ($clients) {
            $clients->load('providers');
            foreach ($clients as $client) {
                if (!$client['sex'] && $client['sex'] !== 0) {
                    $openid = $client->providers->where('provider', 'weixin')->pluck('provider_id')->first();
                    try {
                        $weixin_info = \EasyWeChat::user()->get($openid);
                        if ($weixin_info['subscribe']) {
                            $client->sex = $weixin_info['sex'];
                            $client->save();
                            echo 'update ' . $client['user_id'] . ' sex as ' . $weixin_info['sex']. "\n";
                        }
                    } catch (\Exception $e) {
                        echo $e->getMessage() . "\n";
                    }

                }
            }
        });
    }
}
