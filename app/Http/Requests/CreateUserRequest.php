<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => ['required' , 'string'],
            'last_name'  => ['nullable' , 'string'],
            'username'   => ['required' , 'string', 'unique:users,username,NULL,id,pipeline_id,'.Auth::user()->pipeline_id],
            'password'   => ['required' , 'string', 'min:8', 'regex:/[!@#$%^&*(),.?":{}|<>]/', 'confirmed'],
            'team_id'    => ['nullable' , 'numeric' , 'exists:teams,id'],
            'gender'     => ['required' , 'string'],
            'email'      => ['nullable' , 'string', 'unique:users,email,NULL,id,pipeline_id,'.Auth::user()->pipeline_id],
        ];
    }
}
