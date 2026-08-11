<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\AssetImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     * Usage: php artisan assets:import path/to/file.xlsx
     */
    protected $signature = 'assets:import {file : The path to the excel file}';

    /**
     * The console command description.
     */
    protected $description = 'Update and insert assets from an Excel spreadsheet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found at: {$filePath}");
            return Command::FAILURE;
        }

        $this->info('Starting asset import and update process...');

        try {
            Excel::import(new AssetImport, $filePath);
            $this->info('Assets successfully updated and imported!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('An error occurred during import: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
