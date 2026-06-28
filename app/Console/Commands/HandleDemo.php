<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;
use Carbon\Carbon;

class HandleDemo extends Command
{
    protected $signature = 'handle:demo';
    protected $description = 'Remove demo accounts that didnt login in pase 15 days';

    public function handle()
    {
        $clients = Client::where('account_type','Demo')
            ->where('broker_id', '!=', null)
            ->where('loggedAt', '<', Carbon::now()->subDays(15))
            ->get();

        foreach ($clients as $client) {

            $client->orders()->delete();
            
            foreach ($client->moneyTrx as $trx) {
                $trx->details()->delete();
                $trx->delete();
            }

            $client->broker_id = null;
            $client->account_type = null;
            $client->save();
        }

        $this->info('Demo accounts cleaned up successfully.');
    }
}
