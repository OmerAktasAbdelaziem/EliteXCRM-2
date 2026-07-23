<?php

namespace App\Console\Commands;

use App\Imports\GSheetImport;

use App\Models\AdHandler;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class CaptureLeads extends Command
{
    protected $signature = 'capture:leads';

    protected $description = 'Capture leads from a Google Sheet link';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $ads = AdHandler::all();

        $results = [];
        foreach ($ads as $ad) {
            if($ad->fields->isEmpty()){
                $this->error("Ad with id $ad->id fields are not set yet, not imported");
                continue;
            }
            $filePath = "downloaded_sheet.xlsx";
            $localPath = storage_path('app/temp/' . $filePath);
        
            $this->downloadGoogleSheet($ad, $localPath);
        
            if (!file_exists($localPath)) {
                $this->error("Failed to download the Google Sheet.");
                continue;
            }
        
            $request = new Request();
            $uploadedFile = new \Illuminate\Http\UploadedFile($localPath, $filePath, null, null, true);
            $request->files->set('excel_file', $uploadedFile);
        
            try {
                $results[] = $this->sheetUpload($ad, $request);
            } catch (ValidationException $e) {
                $this->error("Validation Error: " . implode(", ", $e->errors()['excel_file'] ?? ['Unknown error']));
            } catch (\Exception $e) {
                $this->error("An error occurred: {$e->getMessage()}");
            }
        }
        
        return $results;
    }

    public function downloadGoogleSheet(AdHandler $ad, $localPath)
    {
    
        $client = new \GuzzleHttp\Client();
        $response = $client->get($ad->sheet_xlsx_url);

        if ($response->getStatusCode() == 200) {
            file_put_contents($localPath, $response->getBody());
            $this->info('Google Sheet downloaded successfully.');
        } else {
            throw new \Exception("Failed to download Google Sheet.");
        }
    }

    public function sheetUpload(AdHandler $ad, Request $request)
    {
        $import = new GSheetImport($ad);

        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        HeadingRowFormatter::default('none');
        Excel::import($import, $path);

        if(file_exists($path)){
            unlink($path);
        }

        $this->info("Sheet uploaded and processed successfully.");
    }
}

/*
namespace App\Console\Commands;

use App\Imports\GSheetImport;
use App\Models\AdHandler;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class CaptureLeads extends Command
{
    protected $signature = 'capture:leads';
    protected $description = 'Capture leads from Google Sheet safely';

    public function handle()
    {
        // معالجة إعلان واحد فقط في كل دورة لتجنب ضغط السيرفر
        $ad = AdHandler::where('is_active', 1)
            ->orderBy('last_processed_at', 'asc')
            ->first();

        if (!$ad) {
            $this->info("No active ads to process.");
            return;
        }

        if ($ad->fields->isEmpty()) {
            $this->error("Ad ID {$ad->id} fields are not set.");
            return;
        }

        $this->info("Processing Ad ID: {$ad->id}");

        $tempPath = 'temp/sheet_' . $ad->id . '.xlsx';
        
        try {
            // 1. تحميل الملف
            $this->downloadGoogleSheet($ad->sheet_xlsx_url, storage_path('app/' . $tempPath));

            // 2. تجهيز الملف للـ Import
            $request = new Request();
            $uploadedFile = new UploadedFile(storage_path('app/' . $tempPath), 'sheet.xlsx', null, null, true);
            $request->files->set('excel_file', $uploadedFile);

            // 3. الاستيراد
            $this->sheetUpload($ad, $request);

            // 4. تحديث وقت المعالجة
            $ad->update(['last_processed_at' => now()]);
            $this->info("Ad ID {$ad->id} processed successfully.");

        } catch (\Exception $e) {
            $this->error("Error in Ad ID {$ad->id}: " . $e->getMessage());
        } finally {
            // تنظيف الملف المؤقت دائماً
            if (Storage::exists($tempPath)) {
                Storage::delete($tempPath);
            }
        }
    }

    private function downloadGoogleSheet($url, $localPath)
    {
        $client = new \GuzzleHttp\Client(['timeout' => 30]);
        $response = $client->get($url);

        if ($response->getStatusCode() == 200) {
            file_put_contents($localPath, $response->getBody());
        } else {
            throw new \Exception("Failed to download Google Sheet.");
        }
    }

    private function sheetUpload(AdHandler $ad, Request $request)
    {
        $import = new GSheetImport($ad);
        HeadingRowFormatter::default('none');
        
        Excel::import($import, $request->file('excel_file')->getPathname());
    }
}
*/