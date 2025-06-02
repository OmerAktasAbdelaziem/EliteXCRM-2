<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_SmtpTransport;

class PrivateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($data)
    {
        $this->mail_data = $data;
        $this->email     = $this->mail_data['email'];
    }


    public function build()
    {
        $smtpHost = env('MAIL_HOST');
        $smtpPort = env('MAIL_PORT');
        $encryption = env('MAIL_ENCRYPTION');
        $username = $this->mail_data['email_username'];
        $password = $this->mail_data['email_password'];

        $transport = new Swift_SmtpTransport($smtpHost, $smtpPort, $encryption);
        $transport->setUsername($username);
        $transport->setPassword($password);

        $mailer = new Swift_Mailer($transport);

        Mail::setSwiftMailer($mailer);

        return $this->from($this->email, $this->mail_data['company'])
        ->subject($this->mail_data['subject'])
        ->view('email.'.$this->mail_data['type'],[
            'data' => $this->mail_data
        ]);
    }
}
