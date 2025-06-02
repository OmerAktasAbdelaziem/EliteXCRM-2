<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePartRequest extends FormRequest
{

    public function rules()
    {
        return [
            'leader_id' => ['nullable' , 'numeric' , 'exists:users,id'],
            'role_id'   => ['nullable' , 'numeric' , 'exists:roles,id'],
            'teams.*'   => ['nullable' , 'numeric' , 'min:1' , 'exists:teams,id'],
            'teams'     => ['nullable' , 'array' , 'min:1'],
            'name'      => ['required' , 'string' , 'unique:parts,name,NULL,id,pipeline_id,'.Auth::user()->pipeline_id],
        ];
    }
}
