<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImport implements ToModel, WithHeadingRow
{
    public $repeated = 0;
    public $success = 0;
    public $empty = 0;

    public $headers = [];

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function model(array $row)
    {
        /*print_r($this->headers);
        echo '<br><br><br><br><br><br>';
        print_r($row);die;*/
        $mappedRow = [];
        
        foreach ($this->headers as $header => $field) {
            $header = strtolower($header);
            if(isset($row[$header])){
            $mappedRow[$field] = $row[$header];
            }
        }
        //dd($mappedRow);
        $validator = Validator::make($mappedRow, [
            'first_name' => ['required'],
            'country'    => ['required'],
            'phone1'     => ['required'],
            'email'      => ['required'],
        ]);

        if ($validator->fails()) {
          //  print_r($validator->errors());die;
            $this->empty++;
            return null;
        }

        $existingClient = Client::where(function($query) use ($mappedRow) {
            $phone = str_replace(' ', '', trim($mappedRow['phone1']));

            if (strpos($phone, '0') === 0) {
                $trimmedPhone = substr($phone, 1);
                $query->where('phone1', 'like', "%$trimmedPhone%")
                    ->orWhere('phone2', 'like', "%$trimmedPhone%")
                    ->orWhere('phone1', 'like', "%$trimmedPhone")
                    ->orWhere('phone2', 'like', "%$trimmedPhone")
                    ->orWhere('phone1', $trimmedPhone)
                    ->orWhere('phone2', $trimmedPhone);
            } elseif (strpos($phone, '+') === 0) {
                $trimmedPhone = substr($phone, 4);
                $query->where('phone1', 'like', "%$trimmedPhone%")
                    ->orWhere('phone2', 'like', "%$trimmedPhone%")
                    ->orWhere('phone1', 'like', "%$trimmedPhone")
                    ->orWhere('phone2', 'like', "%$trimmedPhone")
                    ->orWhere('phone1', $trimmedPhone)
                    ->orWhere('phone2', $trimmedPhone);
            } else {
                $trimmedPhone = substr($phone, 3);
                $query->where('phone1', 'like', "%$trimmedPhone%")
                    ->orWhere('phone2', 'like', "%$trimmedPhone%")
                    ->orWhere('phone1', 'like', "%$trimmedPhone")
                    ->orWhere('phone2', 'like', "%$trimmedPhone")
                    ->orWhere('phone1', $trimmedPhone)
                    ->orWhere('phone2', $trimmedPhone);
            }
        })->where('source', $mappedRow['source'] ?? null)->where('deleted', 0)->first();

        if ($existingClient) {
            $this->repeated++;
            return null;
        }

        $inputs = [
            'sales_status' => $mappedRow['sales_status'] ?? 'New',
            'pipeline_id'  => Auth::user()->pipeline_id,
            'created_by'   => Auth::user()->username,
            'first_name'   => $mappedRow['first_name'] ?? null,
            'last_name'    => $mappedRow['last_name'] ?? null,
            'how_money'    => $mappedRow['how_money'] ?? null,
            'campaign'     => $mappedRow['campaign'] ?? null,
            'country'      => $mappedRow['country'] ?? null,
            'phone1'       => $mappedRow['phone1'] ?? null,
            'gender'       => $mappedRow['gender'] ?? null,
            'source'       => $mappedRow['source'] ?? null,
            'phone2'       => $mappedRow['phone2'] ?? null,
            'email'        => $mappedRow['email'] ?? null,
            'age'          => $mappedRow['age'] ?? null,
            'ad'           => $mappedRow['ad'] ?? null,
        ];

        if (isset($mappedRow['is_have_invest'])) {
            $inputs['is_have_invest'] = $mappedRow['is_have_invest'] === null ? null  : (in_array($mappedRow['is_have_invest'], ['Yes', 'نعم']) ? 1 : 0);
        }
        if (isset($mappedRow['is_have_money'])) {
            $inputs['is_have_money'] =  $mappedRow['is_have_money'] === null ? null : (in_array($mappedRow['is_have_money'], ['Yes', 'نعم']) ? 1 : 0);
        }
        if (isset($mappedRow['is_have_time'])) {
            $inputs['is_have_time'] =  $mappedRow['is_have_time'] === null ? null : (in_array($mappedRow['is_have_time'], ['Yes', 'نعم']) ? 1 : 0);
        }
        if (isset($mappedRow['is_25'])) {
            $inputs['is_25'] = $mappedRow['is_25'] === null ? null : (in_array($mappedRow['is_25'], ['Yes', 'نعم']) ? 1 : 0);
        }

        Client::create($inputs);

        $this->success++;

        return null;
    }
}
