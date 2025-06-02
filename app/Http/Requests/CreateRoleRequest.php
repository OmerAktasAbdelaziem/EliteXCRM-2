<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRoleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'    => ['required' , 'string' , 'unique:roles,name,NULL,id,pipeline_id,'.Auth::user()->pipeline_id],
            'users.*' => ['nullable' , 'numeric' , 'exists:users,id'],
            'teams.*' => ['nullable' , 'numeric' , 'exists:teams,id'],
            'parts.*' => ['nullable' , 'numeric' , 'exists:parts,id'],
            'users'   => ['nullable' , 'array'],
            'teams'   => ['nullable' , 'array'],
            'parts'   => ['nullable' , 'array'],
        ];
    }
}
