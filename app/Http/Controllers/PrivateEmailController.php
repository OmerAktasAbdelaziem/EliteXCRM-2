<?php

namespace App\Http\Controllers;

use App\Mail\PrivateMail;
use App\Models\Action;
use App\Models\Client;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PrivateEmailController extends Controller
{
    public function sendEmail(Request $request,$id,$type)
    {
        $client  = Client::find($id);
        $email   = $client->email;
        $inputs = [];
        if ($type == 'login') {
            if ($client->broker_id && $client->account_type == 'Demo') {
                $data = [
                    'company_url' => 'https://dashboard.bnc-ltd.co.uk',
                    'username'    => $client->username,
                    'password'    => $client->password_text,
                    'logo_url'    => url('assets/images/bnc-logo.png'),
                    'company'     => 'BNC',
                    'subject'     => 'Login details',
                    'email'       => 'no-reply@bnc-ltd.co.uk',
                    'type'        => 'login',
                    'name'        => $client->first_name.' '.$client->last_name,
                ];
                if ($client->source != 'BNC') {
                    $data['company_url'] = 'https://dashboard.phooenixs.com';
                    $data['logo_url']    = 'assets/images/phoenix-logo.png';
                    $data['company']     = 'Phoenix FX';
                    $data['email']       = 'support@phooenixs.com';
                }
            }

            $inputs = array_merge($inputs, [
                'company_url' => $data['company_url'],
                'username'    => $data['username'],
                'password'    => $data['password'],
            ]);
        }

        if ($type == 'ftd') {
            if ($client->broker_id && $client->account_type == 'Demo') {
                $data = [
                    'wallet_id' => $client->broker_id,
                    'logo_url'  => url('assets/images/bnc-logo.png'),
                    'subject'   => 'Your deposit is approved',
                    'company'   => 'BNC',
                    'amount'    => $request->amount,
                    'email'     => 'no-reply@bnc-ltd.co.uk',
                    'name'      => $client->first_name.' '.$client->last_name,
                    'type'      => 'ftd',
                ];
                if ($client->source != 'BNC') {
                    $data['logo_url'] = 'assets/images/phoenix-logo.png';
                    $data['company']  = 'Phoenix FX';
                    $data['email']    = 'support@phooenixs.com';
                }
            }

            $inputs = array_merge($inputs, [
                'wallet_id' => $data['wallet_id'],
                'amount'    => $data['amount'],
            ]);
        }

        if ($type == 'demo' || $type == 'real') {
            list($email, $data, $realOrDemoInputs) = $this->realOrDemo($request, $id, $type);
            $inputs = array_merge($inputs,$realOrDemoInputs);
        }

        $inputs = array_merge($inputs, [
            'from_email' => $data['email'],
            'client_id'  => $client->id,
            'to_email'   => $email,
            'user_id'    => Auth::id(),
            'company'    => $data['company'],
            'type'       => $data['type'],
            'name'       => $data['name'],
        ]);
    
        $action = Action::create([
            'client_id' => $id,
            'user_id'   => Auth::id(),
            'text'      => 'Sent '. $data['type'].' Email To ' . $email . ' From ' . $data['email']
        ]);
    
        Log::channel('telegram')->info(Auth::user()->username.' '.$action->text.' On '.$action->client->first_name.' '.$action->client->last_name);
    
        EmailLog::create($inputs);

        if ($client->source != 'BNC') {
            $data['email_username'] = env('MAIL_USERNAME');
            $data['email_password'] = env('MAIL_PASSWORD');
        }else{
            $data['email_username'] = env('MAIL_USERNAME_BNC');
            $data['email_password'] = env('MAIL_PASSWORD_BNC');
        }
        
        Mail::to($email)->send(new PrivateMail($data));

        return redirect()->back()->with('success', 'Email has been sent successfully.');
    }

    public function realOrDemo(Request $request,$id,$type)
    {
        $client = Client::find($id);
        $subject = 'Login details';
        if ($client->source != 'BNC') {
            $companyUrl = 'Phoenix FX';
            $baseEmail  = 'support@phooenixs.com';
            $url        = url('assets/images/phoenix-logo.png');
        }else{
            $companyUrl = 'BNC';
            $baseEmail  = 'no-reply@bnc-ltd.co.uk';
            $url        = url('assets/images/bnc-logo.png');
        }

        $data = [
            'username' => $client->username,
            'password' => $client->password_text,
            'logo_url' => $url,
            'company'  => $companyUrl,
            'subject'  => $subject,
            'email'    => $baseEmail,
            'name'     => $client->first_name.' '.$client->last_name,
            'type'     => $type,
        ];

        if ($request->amount) {
            $data['amount'] = $request->amount;
        }

        $realInputs = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        return [$client->email, $data, $realInputs];
    }

    public function previewEmail(Request $request,$id)
    {
        $email_log  = EmailLog::find($id);
        $inputs = [];
        if ($email_log['type'] == 'login') {
            $data = [
                'company_url' => $email_log['company_url'],
                'username'    => $email_log['username'],
                'password'    => $email_log['password'],
                'logo_url'    => $email_log['logo_url'],
                'company'     => $email_log['company'],
                'email'       => $email_log['from_email'],
                'type'        => $email_log['type'],
                'name'        => $email_log['name'],
            ];
        }

        if ($email_log['type'] == 'ftd') {
            $data = [
                'wallet_id' => $email_log['wallet_id'],
                'logo_url'  => $email_log['logo_url'],
                'company'   => $email_log['company'],
                'amount'    => $email_log['amount'],
                'email'     => $email_log['email'],
                'name'      => $email_log['name'],
                'type'      => $email_log['type'],
            ];
        }

        if ($email_log['type'] == 'demo') {
            $data = [
                'username' => $email_log['username'],
                'password' => $email_log['password'],
                'logo_url' => $email_log['logo_url'],
                'company'  => $email_log['company'],
                'amount'   => $email_log['amount'],
                'email'    => $email_log['from_email'],
                'type'     => $email_log['type'],
                'name'     => $email_log['name'],
            ];
        }

        if ($email_log['type'] == 'real') {
            $data = [
                'username' => $email_log['username'],
                'password' => $email_log['password'],
                'logo_url' => $email_log['logo_url'],
                'company'  => $email_log['company'],
                'email'    => $email_log['from_email'],
                'type'     => $email_log['type'],
                'name'     => $email_log['name'],
            ];
        }

        EmailLog::create($inputs);
        
        return view('email.'.$email_log['type'],compact('data'));
    }
}