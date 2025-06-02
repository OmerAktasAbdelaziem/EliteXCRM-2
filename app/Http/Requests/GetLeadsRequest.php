<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetLeadsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'campaign_id' => ['nullable' , 'string'],
            'first_name'  => ['nullable' , 'string'],
            'last_name'   => ['nullable' , 'string'],
            'campaign'    => ['nullable' , 'string'],
            'country'     => ['nullable' , 'string'],
            'source'      => ['nullable' , 'string'],
            'phone1'      => ['nullable' , 'string'],
            'email'       => ['nullable' , 'string'],
            'age'         => ['nullable' , 'numeric'],
            'ad'          => ['nullable' , 'string'],
        ];
    }
}
