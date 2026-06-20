<?php

namespace App\Console\Commands;

use App\Models\AdHandler;
use App\Models\Client;
use App\Services\CountryNormalizer;
use Illuminate\Console\Command;

class NormalizeCountries extends Command
{
    protected $signature = 'country:normalize';
    protected $description = 'normalize all countries';

    public function handle()
    {
        Client::chunk(1000, function ($clients) {
            foreach ($clients as $client) {
                $normalized = CountryNormalizer::normalize($client->country);

                if ($normalized) {
                    $client->update([
                        'country' => $normalized,
                    ]);
                }
            }
        });

        AdHandler::chunk(1000, function ($ads) {
            foreach ($ads as $ad) {
                $normalized = CountryNormalizer::normalize($ad->sheet_country);

                if ($normalized) {
                    $ad->update([
                        'sheet_country' => $normalized,
                    ]);
                }
            }
        });
    }
}
