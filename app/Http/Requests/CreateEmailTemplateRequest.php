<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmailTemplateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'attachment.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt', 'max:2048'],
            'subject'      => ['required', 'string'],
            'name'         => ['required', 'string'],
            'body'         => ['required'],
        ];
    }
}
