<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CreateSubscriptionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start_date'     => ['required', 'date_format:Y-m-d\TH:i'],
'end_date'       => ['required', 'date_format:Y-m-d\TH:i'],
//'pipeline'     => ['required', 'numeric'],
//'users_count'  => ['nullable', 'numeric'],
//'parts_count'  => ['nullable', 'numeric'],
//'teams_count'  => ['nullable', 'numeric'],
        ];
    }
}
