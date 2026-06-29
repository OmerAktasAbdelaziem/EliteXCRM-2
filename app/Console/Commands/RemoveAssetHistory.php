<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\AssetsHistory;

class RemoveAssetHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:assets-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove assets history, but keep last 10 minutes.';

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
     * @return int
     */
    public function handle()
    {
     //   AssetsHistory::where('created_at', '<', Carbon::now()->subMinutes(10))->delete();
        //return 0;
        
        //not needed anymore
    }
}
