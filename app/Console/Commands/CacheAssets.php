// <?php
// // app/Console/Commands/CacheAssets.php
// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Asset;
// use Illuminate\Support\Facades\Cache;

// class CacheAssets extends Command
// {
//     protected $signature = 'assets:cache';
//     protected $description = 'Cache all assets from the database every second';

//     public function handle()
//     {
//         $assets = Asset::all();
//         Cache::put('all_assets', $assets, 2);
//         $this->info('Assets cached at ' . now());
//     }
// }