<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckOnline extends Command
{
    protected $signature = 'check:online';
    protected $description = 'check who is online from clients';

    public function handle()
    {die;
        $loop = true;
        while($loop){
            $clients = Client::where('is_online', 1)->where('loggedAt', '<', Carbon::now()->subSeconds(10))->get();
            foreach ($clients as $client) {
                if ($client->is_online == 1) {
                    $client->is_online = 0;
                    $client->last_seen_at = now();
                    $client->save();
                }
            }
        }
    }
    
}
