<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;
use App\Models\Transactions;
use App\Helpers\MessageBrokerHttp;

class TransactionsController extends Controller
{
    private $connection;
    private $channel;

    private $host = "192.168.0.7";
    private $port = 5672;
    private $user = "guest";
    private $password = "guest";
    private $vhost = "/";

    const base_url = "notification-service-picpay/public/";

    public function connect()
    {
        try {
            $this->connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password, $this->vhost);
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function payments()
    {
        $this->connect();

        $this->channel->queue_declare(
            "transaction",  #queue 
            false,          #passive
            false,          #durable
            false,          #exclusive
            false           #autodelete
        );

        $this->channel->basic_qos(
            null,   #prefetch size
            1,      #prefetch count
            null    #global
        );

        $this->channel->basic_consume(
            "transaction",              #queue
            '',                         #consumer tag
            false,                      #no local
            false,                      #no ack
            false,                      #exclusive
            false,                      #no wait
            array($this, "callback")    #callback
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->closeConnection();
    }

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function callback(AMQPMessage $req)
    {
        $data = json_decode($req->body);
        $result = $this->processTransaction($data);

        $msg = new AMQPMessage(
            json_encode($result),
            array('correlation_id' => $req->get('correlation_id'))  #options
        );

        $req->delivery_info['channel']->basic_publish(
            $msg,                   #message
            '',                     #exchange
            $req->get('reply_to')   #routing key
        );

        $req->delivery_info['channel']->basic_ack(
            $req->delivery_info['delivery_tag']
        );
    }

    private function processTransaction(\stdClass $object)
    {
        $valid = $this->validateTransaction($object->value);

        if (!$valid) {
            return [
                "code" => 400,
                "status" => "Unauthorized Transaction",
                "message" => "Transactions whose value is greater than or equal to R$ 100.00"
            ];
        }

        $this->registerTransactions($object);

        $this->sendEmail($object);

        return [
            "code" => 200,
            "status" => "Authorized Transaction",
            "message" => "Transaction was successful"
        ];
    }

    private function validateTransaction($value)
    {
        if ($value >= 100) {
            return false;
        }
        return true;
    }

    private function registerTransactions($object)
    {
        $result = Transactions::create([
            "payee_id" => $object->payee,
            "payer_id" => $object->payer,
            "transaction_date" => $object->transactionDate,
            "value" => $object->value
        ]);

        if (!$result) {
            return false;
        }
        return true;
    }

    private function sendEmail($data)
    {
        $datas = [
            "payer" => $data->payer,
            "payee" => $data->payee,
            "email" => "teste@picppay.com.br",
            "value" => $data->value
        ];

        $http = new MessageBrokerHttp();
        $http->execRequest('POST', self::base_url . "email/transaction", $datas);
    }
}
