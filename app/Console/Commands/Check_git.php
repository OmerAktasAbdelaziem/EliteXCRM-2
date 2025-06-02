<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Check_git extends Command
{
    protected $signature = 'check:git';

    protected $description = 'Check git pull req and install dep.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Perform deployment tasks (e.g., pull the latest changes from the main branch, build the project, etc.)
        chdir('/home/u420350257/domains/elitexcrm.com/public_html/NewEliteXCRM');
        
        $output = shell_exec('git pull origin main');

        // Check if the output contains "Already up to date."
        if (strpos($output, 'Already up to date.') !== false) {
            echo $output . "\n";
            Log::info('cron output', $output. "\n");
            return 0;
        }
        // Check if there are changes
        $diffOutput = shell_exec('git diff HEAD@{1} HEAD');
        if (!empty($diffOutput)) {
            // Run the cron job if there are changes
            echo "Running commands...\n";
            
            // Execute individual commands and capture output
            $output1 = shell_exec('php artisan optimize');
            $output2 = shell_exec('php artisan migrate');
            $output3 = shell_exec('composer install');
            
            // Display the output of git pull
            echo "Output of git pull:\n";
            echo $output;
            
            // Display command outputs
            echo "Executing command.sh\n";
            echo "Optimizing cache...\n";
            echo $output1;
            echo "Running migrations...\n";
            echo $output2;
            echo "Installing Composer dependencies...\n";
            echo $output3;
        } else {
            echo $output . "\n";
            Log::info('cron output', $output. "\n");
        }
        
        return 0;
    }
}
