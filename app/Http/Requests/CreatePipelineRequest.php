<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePipelineRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_id' => ['required' , 'numeric'],
            'part_limit'  => ['nullable' , 'numeric'],
            'team_limit'  => ['nullable' , 'numeric'],
            'user_limit'  => ['nullable' , 'numeric'],
            'name'        => ['required' , 'string' , 'unique:pipelines,name'],
        ];
    }
}
