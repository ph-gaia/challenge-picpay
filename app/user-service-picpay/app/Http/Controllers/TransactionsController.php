<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Config\Configuration as cfg;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $url = cfg::BASE_URL_MOCK . "/payments";
        $data = [
            "payee" => $request->get('payee'),
            "payer" => $request->get('payer'),
            "transactionDate" => $request->get('transactionDate'),
            "value" => $request->get('value')
        ];
        return self::makeTransactions($url, $data);
    }

    /**
     * Method responsible for communicating with the API with the HTTP POST verb
     *
     * @param String URL requisition address
     * @param array Header request header configuration set
     * @return json
     */
    private static function makeTransactions($url, $fields = null, $header = null)
    {
        // Abrindo a conexão
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        if (!empty($fields)) {
            if (is_array($fields)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields, '', '&'));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            }
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        // Executando requisição
        $response = curl_exec($ch);

        if ($response === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Fechando conexão
        curl_close($ch);

        return $response;
    }
}