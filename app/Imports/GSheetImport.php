<?php

namespace App\Imports;

use App\Models\Client;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $lastCapturedTimestamp = Client::where('form_id',$row['form_id'])->max('last_captured_at') ?: '1970-01-01 00:00:00';

        $originalTimestamp = isset($row['created_at']) ? Carbon::parse($row['created_at']) : null;

        if ($originalTimestamp) {
            $istanbulTimestamp = $originalTimestamp->setTimezone('Europe/Istanbul');
            $leadTimestamp = $istanbulTimestamp->format('Y-m-d H:i:s');
        } else {
            $leadTimestamp = null;
        }

        if ($leadTimestamp && $leadTimestamp > $lastCapturedTimestamp) {
            $adMap = [
                'ag:120223300533320286' => 'Libya',
                'ag:120223299670150286' => 'Iraq',
            ];
            Client::create([
                'last_captured_at' => $leadTimestamp,
                'is_have_invest'   => $row['is_have_invest'] === 'نعم' ? 1 : ($row['is_have_invest'] === 'لا' ? 0 : null),
                'is_have_money'    => $row['is_have_money'] === 'نعم' ? 1 : ($row['is_have_money'] === 'لا' ? 0 : null),
                'is_have_time'     => $row['is_have_time'] === 'نعم' ? 1 : ($row['is_have_time'] === 'لا' ? 0 : null),
                'created_by'       => 'Google Sheet',
                'first_name'       => $row['first_name'] ?? null,
                'created_at'       => $leadTimestamp,
                'last_name'        => $row['last_name'] ?? null,
                'campaign'         => $row['campaign'] ?? null,
                'country'          => ($row['ad_name'] == '2' && isset($adMap[$row['ad_id']])) ? $adMap[$row['ad_id']] : ($row['ad_name'] ?? null),
                'phone1'           => $row['phone1'] ?? null,
                'phone2'           => isset($row['phone2']) ? (substr($row['phone2'], 0, 2) === 'p:' ? substr($row['phone2'], 2) : $row['phone2']) : null,
                'source'           => 'Facebook Form',
                'email'            => $row['email'] ?? null,
                'is_25'            => $row['is_25'] === 'نعم' ? 1 : ($row['is_25'] === 'لا' ? 0 : null),
                'ad'               => $row['ad'] ?? null,
                'form_id'          => $row['form_id'],
            ]);
        }
    }
}
