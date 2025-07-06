<?php

namespace App\Console\Commands;

use App\Imports\GSheetImport;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

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
        $sheetIds = [
            "15vK_tgBTiaWly8U07h1zgOcwiel-9BJU-ZdW3IIEe2g",
            "1rk2ewMyqg3eyKNfxRWuA1qo8R4IUgkQfIJwsoZIW2r4",
        ];
        
        $results = [];
        
        foreach ($sheetIds as $sheetId) {
            $filePath = "downloaded_sheet.xlsx";
            $localPath = storage_path('app/temp/' . $filePath);
        
            $this->downloadGoogleSheet($sheetId, $localPath);
        
            if (!file_exists($localPath)) {
                $this->error("Failed to download the Google Sheet.");
                continue;
            }
        
            $request = new Request();
            $uploadedFile = new \Illuminate\Http\UploadedFile($localPath, $filePath, null, null, true);
            $request->files->set('excel_file', $uploadedFile);
        
            try {
                $results[] = $this->sheetUpload($request);
            } catch (ValidationException $e) {
                $this->error("Validation Error: " . implode(", ", $e->errors()['excel_file'] ?? ['Unknown error']));
            } catch (\Exception $e) {
                $this->error("An error occurred: {$e->getMessage()}");
            }
        }
        
        return $results;
    }

    public function downloadGoogleSheet($sheetId, $localPath)
    {
        $url = "https://docs.google.com/spreadsheets/d/$sheetId/export?format=xlsx";
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        if ($response->getStatusCode() == 200) {
            file_put_contents($localPath, $response->getBody());
            $this->info('Google Sheet downloaded successfully.');
        } else {
            throw new \Exception("Failed to download Google Sheet.");
        }
    }

    public function sheetUpload(Request $request)
    {
        $import = new GSheetImport();

        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;

        Excel::import($import, $path);
        $this->info("Sheet uploaded and processed successfully.");
    }
}
