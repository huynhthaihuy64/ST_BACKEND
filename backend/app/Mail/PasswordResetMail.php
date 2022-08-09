<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param int $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $mailTemplete
     */
    public function build()
    {
        return $this->view("mailTemplete")->with("code" , $this->code);
    }
}
