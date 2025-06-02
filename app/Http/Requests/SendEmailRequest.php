<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'body'            => ['nullable', 'string'],
            'subject'         => ['nullable', 'string'],
            'template_id'     => ['nullable', 'numeric', 'exists:email_templates,id'],
            'attachment.*'    => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt', 'max:2048'],
            'sender_email_id' => ['required', 'numeric', 'exists:sender_emails,id'],
        ];
    }
}
