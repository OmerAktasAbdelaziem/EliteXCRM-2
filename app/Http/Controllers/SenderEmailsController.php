<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSenderEmailRequest;
use App\Models\SenderEmail;
use Illuminate\Http\Request;

class SenderEmailsController extends Controller
{
    public function index()
    {
        $senderEmails = SenderEmail::latest()->get();

        return view('sender_emails.index', compact('senderEmails'));
    }

    public function create()
    {
        $senderEmail = new SenderEmail;
        
        return view('sender_emails.show', compact('senderEmail'));
    }

    public function store(CreateSenderEmailRequest $request)
    {
        $inputs = $request->only('company_name', 'email', 'username', 'password', 'host', 'port', 'encryption');

        SenderEmail::create($inputs);
        
        return redirect()->route('sender_emails.index')->with('success', 'Sender email created successfully');
    }

    public function show($id)
    {
        $senderEmail = SenderEmail::findOrFail($id);
        
        return view('sender_emails.show', compact('senderEmail'));
    }

    public function update(CreateSenderEmailRequest $request,$id)
    {
        $senderEmail = SenderEmail::findOrFail($id);

        $inputs = $request->only('company_name', 'email', 'username', 'password', 'host', 'port', 'encryption');

        $senderEmail->update($inputs);
        
        return redirect()->back()->with('success', 'Sender email updated successfully');
    }

    public function delete($id)
    {
        $senderEmail = SenderEmail::findOrFail($id);

        $senderEmail->delete();
        
        return redirect()->route('sender_emails.index')->with('success', 'Sender email deleted successfully');
    }
}
