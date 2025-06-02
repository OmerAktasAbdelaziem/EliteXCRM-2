<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_SmtpTransport;

class MarketingMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mail_data;
    protected $email;

    public function __construct($data)
    {
        $this->mail_data = $data;
        $this->email     = $this->mail_data['email'];
    }


    public function build()
    {
        $encryption = $this->mail_data['encryption'];
        $smtpHost   = $this->mail_data['host'];
        $smtpPort   = $this->mail_data['port'];
        $username   = $this->mail_data['username'];
        $password   = $this->mail_data['password'];

        $transport = new Swift_SmtpTransport($smtpHost, $smtpPort, $encryption);
        $transport->setUsername($username);
        $transport->setPassword($password);

        $mailer = new Swift_Mailer($transport);

        Mail::setSwiftMailer($mailer);

        $mail = $this->from($this->email, $this->mail_data['company_name'])
        ->subject($this->mail_data['subject'])
        ->view('email.marketing',[
            'data' => $this->mail_data
        ]);

        if (isset($this->mail_data['attachment'])) {
            foreach ($this->mail_data['attachment'] as $attachment) {
                $mail->attach($attachment);
            }
        }

        return $mail;
    }
}
