<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTeamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'leader_id' => ['nullable' , 'numeric' , 'exists:users,id'],
            'members.*' => ['nullable' , 'numeric' , 'exists:users,id'],
            'members'   => ['nullable'],
            'part_id'   => ['required' , 'numeric' , 'exists:parts,id'],
            'name'      => ['required' , 'string' , 'unique:teams,name,NULL,id,pipeline_id,'.Auth::user()->pipeline_id],
        ];
    }
}
