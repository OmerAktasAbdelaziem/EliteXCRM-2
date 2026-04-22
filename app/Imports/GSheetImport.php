<?php

namespace App\Imports;

use App\Models\AdHandler;
use App\Models\Client;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class GSheetImport implements ToModel, WithHeadingRow
{
    protected AdHandler $ad;

    public function __construct(AdHandler $ad)
    {
        $this->ad = $ad;
    }

    public function model(array $row)
    {
        $lastCapturedTimestamp = Client::where('pipeline_id', $this->ad->pipeline_id)
            ->where('form_id', $row['form_id'])
            ->max('last_captured_at') ?: '1970-01-01 00:00:00';

        $originalTimestamp = isset($row['created_time']) ? Carbon::parse($row['created_time']) : null;

        if ($originalTimestamp) {
            $istanbulTimestamp = $originalTimestamp->setTimezone('Europe/Istanbul');
            $leadTimestamp = $istanbulTimestamp->format('Y-m-d H:i:s');
        } else {
            $leadTimestamp = null;
        }

        if ($leadTimestamp && $leadTimestamp > $lastCapturedTimestamp) {

            $mappedRow = [];
            $headers = [];
            foreach ($this->ad->fields as $field) {
                $header = trim(strtolower($field->sheet_field));
                $headers[] = $header;
                if(isset($row[$header])){
                    $mappedRow[$field->crm_field] = $row[$header];
                }
            }

            $mappedRow['country'] = $this->ad->sheet_country;

            $validator = Validator::make($mappedRow, [
                'first_name' => ['required'],
                'country'    => ['required'],
                'phone1'     => ['required'],
                'email'      => ['required'],
            ]);

            $validator->validate();

            Client::create([
                'last_captured_at' => $leadTimestamp,
                'pipeline_id'      => $this->ad->pipeline_id,
                'is_have_invest'   => $this->yesNoToBool($mappedRow['is_have_invest'] ?? null),
                'is_have_money'    => $this->yesNoToBool($mappedRow['is_have_money'] ?? null),
                'is_have_time'     => $this->yesNoToBool($mappedRow['is_have_time'] ?? null),
                'is_25'            => $this->yesNoToBool($mappedRow['is_25'] ?? null),
                'created_by'       => 'Google Sheet',
                'first_name'       => $mappedRow['first_name'],
                'created_at'       => $leadTimestamp,
                'last_name'        => $mappedRow['last_name'] ?? null,
                'campaign'         => $mappedRow['campaign'] ?? null,
                'country'          => $mappedRow['country'],
                'phone1'           => $mappedRow['phone1'],
                'phone2'           => $mappedRow['phone2'],
                'source'           => 'Facebook Form',
                'email'            => $mappedRow['email'],
                'ad'               => $mappedRow['ad'] ?? null,
                'form_id'          => $mappedRow['form_id'] ?? $row['form_id'] ?? null,
            ]);
        }
    }

    private function yesNoToBool($value)
    {
        if(!$value){
            return null;
        }
        return match ($value) {
            'نعم' => 1,
            'Yes' => 1,
            default => 0,
        };
    }
}
