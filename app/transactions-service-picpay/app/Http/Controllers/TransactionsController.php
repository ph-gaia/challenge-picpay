<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{
    public function payments()
    {
        $this->gravar("starting receiver...");
        \Amqp::consume('transactions.create', function ($message, $resolver) {

            var_dump($message->body);
            $this->gravar(json_encode($message->body));

            $resolver->acknowledge($message);
        });
    }

    public function registerTransactions($object)
    {
        Transactions::Create([
            "payee" => $object->payee,
            "payer" => $object->payer,
            "transactionDate" => $object->transactionDate,
            "value" => $object->value
        ]);
    }

    public function gravar($texto)
    {
        Log::emergency($texto);
    }
}
