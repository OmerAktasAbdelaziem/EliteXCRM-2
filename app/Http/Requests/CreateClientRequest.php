<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateClientRequest extends FormRequest
{
    public function rules()
    {
        return [
            'sales_status' => ['required' , 'string'],
            'first_name'   => ['required' , 'string'],
            'last_name'    => ['nullable' , 'string'],
            'country'      => ['required' , 'string'],
            'user_id'      => ['nullable' , 'numeric', 'exists:users,id'],
            'source'       => ['nullable' , 'string'],
            'phone1'       => ['required' , 'string'],
            'phone2'       => ['nullable' , 'string'],
            'email'        => [ 'required', 'string', 'unique:clients,email,NULL,id,pipeline_id,'.Auth::user()->pipeline_id],
        ];
    }
}
