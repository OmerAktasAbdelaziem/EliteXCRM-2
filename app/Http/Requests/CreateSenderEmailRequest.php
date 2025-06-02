<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSenderEmailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'encryption' => ['required', 'string'],
            'username'   => ['required', 'string'],
            'password'   => ['required', 'string'],
            'email'      => ['required', 'email'],
            'host'       => ['required', 'string'],
            'port'       => ['required', 'numeric'],
        ];
    }
}
