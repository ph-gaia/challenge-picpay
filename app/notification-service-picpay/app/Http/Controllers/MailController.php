<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\welcomeMail;
use App\Mail\transactionMail;

class MailController extends Controller
{
    /**
     * Build the welcome message.
     *
     * @return $this
     */
    public function welcome(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        Mail::to($email)->send(new welcomeMail($name));

        return 'Email was succefully';
    }

    /**
     * Build the transaction message.
     *
     * @return $this
     */
    public function transaction(Request $request)
    {
        $email = $request->get('email');
        $payer = $request->get('payer');
        $payee = $request->get('payee');
        $value = $request->get('value');
        Mail::to($email)->send(new transactionMail($payer, $payee, $value));

        return 'Email was succefully';
    }
}
