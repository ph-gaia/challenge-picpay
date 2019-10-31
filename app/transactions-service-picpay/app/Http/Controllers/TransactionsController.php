<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Bschmitt\Amqp\Amqp;

class TransactionsController extends Controller
{
    public function payments()
    {
        $amqp = new Amqp();
        $amqp->consume('auth', function ($message, $resolver) {

            var_dump($message->body);

            //$resolver->acknowledge($message);
        });

        /*$transaction = new Transactions();
        $transaction->name = $data->name;
        $transaction->description = $data->description;
        
        $transaction->save();*/
    }
}
