<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MonitorScriptsCommand extends Command
{
    protected $signature = 'monitor:scripts';
    protected $description = 'Monitor specific artisan commands and restart them if they stop running';

    protected $commandsToMonitor = [
        'calculate:pnl',
        'get:assets',
        'get:stock-assets2',
        'get:forex-assets',
        'get:assetsBinance',
        'get:indx-assets',
        'get:stock-assets',
    ];

    public function handle()
    {/*
        $this->info("🔍 Starting script monitoring...");

        $phpPath = trim(shell_exec('which php'));
        $artisanPath = base_path('artisan');
        $projectPath = base_path();
        $pidPath = storage_path('pids');
        $logFile = storage_path('logs/monitor_scripts.log');

        if (!is_dir($pidPath)) {
            mkdir($pidPath, 0777, true);
        }

        while (true) {
            foreach ($this->commandsToMonitor as $cmd) {
                $pidFile = $pidPath . '/' . str_replace(':', '_', $cmd) . '.pid';

                if (!$this->isScriptRunning($pidFile)) {
                    $this->info("❌ $cmd is NOT running. Starting it...");
                    file_put_contents($logFile, now() . " Starting $cmd\n", FILE_APPEND);

                    $fullCommand = "$phpPath $artisanPath $cmd";
                    $this->startScriptInBackground($fullCommand, $projectPath, $logFile, $pidFile);
                } else {
                    $this->info("✅ $cmd is already running.");
                }
            }

            sleep(10);
        }*/
    }

    protected function isScriptRunning(string $pidFile): bool
    {
        if (!file_exists($pidFile)) return false;

        $pid = trim(file_get_contents($pidFile));

        if (!$pid || !is_numeric($pid)) return false;

        $result = shell_exec("ps -p $pid");

        return str_contains($result, (string)$pid);
    }

    protected function startScriptInBackground(string $command, string $path, string $logFile, string $pidFile): void
    {
        //Run script in background and get PID
        $script = "cd $path && $command >> $logFile 2>&1 & echo $!";
        $pid = shell_exec($script);

        file_put_contents($pidFile, trim($pid));
        file_put_contents($logFile, now() . " Started with PID $pid\n", FILE_APPEND);
    }
}