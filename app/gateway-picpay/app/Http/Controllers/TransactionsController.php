<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Config\Configuration as cfg;
use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class TransactionsController extends Controller
{

    public function sendBroker()
    {
        $host = "chimpanzee.rmq.cloudamqp.com";
        $port = 5672;
        $user = "ldqoqigt";
        $password = "sQcB6mPNv0qsLdDQg4lQF7U3LL9TnI0K";
        $vhost = "ldqoqigt";

        $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, true, false, false);
        $msg = new AMQPMessage(
            'Hello World!',
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );
        $channel->basic_publish($msg, '', 'hello');
        echo " [x] Sent 'Hello World!'\n";
        $channel->close();
        $connection->close();
    }

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
