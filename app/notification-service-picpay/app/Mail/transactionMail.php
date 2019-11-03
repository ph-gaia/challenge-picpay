<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class transactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payer, $payee, $value;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payer, $payee, $value)
    {
        $this->payer = $payer;
        $this->payee = $payee;
        $this->value = $value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.transaction');
    }
}
