<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MessageBroker;

class TransactionsController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $data = [
            "payee" => $request->get('payee'),
            "payer" => $request->get('payer'),
            "transactionDate" => $request->get('transactionDate'),
            "value" => $request->get('value')
        ];

        $rabbit = new MessageBroker();
        $result = $rabbit->SendAndListen("transaction", $data);

        return response($result);
    }
}
