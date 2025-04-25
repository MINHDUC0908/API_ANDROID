<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailContact extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $messageContent;

    public function __construct($name, $phone, $messageContent)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->messageContent = $messageContent;
    }

    public function build()
    {
        return $this->view('emails.contact')->with([
            'name' => $this->name,
            'phone' => $this->phone,
            'messageContent' => $this->messageContent,
        ]);
    }
}
