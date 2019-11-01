<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;
use App\Models\Users;

class UsersController extends Controller
{

    private $connection;
    private $channel;

    private $host = "chimpanzee.rmq.cloudamqp.com";
    private $port = 5672;
    private $user = "ldqoqigt";
    private $password = "sQcB6mPNv0qsLdDQg4lQF7U3LL9TnI0K";
    private $vhost = "ldqoqigt";
    

    public function init()
    {
        $this->listenAndSend("users.findname", "findByNameOrUsername");
        $this->listenAndSend("users.findall", "findAll");
        $this->listenAndSend("users.find", "findById");
        $this->listenAndSend("users.register", "create");
        $this->listenAndSend("users.update", "update");
        $this->listenAndSend("users.delete", "destroy");
        // $this->listenAndSend("auth", "login");
    }

    /**
     * open connection and channel
     */
    private function connect()
    {
        try {
            $this->connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password, $this->vhost);
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * close connection and channel
     */
    private function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }

    private function listenAndSend($queue, $callback)
    {
        $this->connect();

        $this->channel->queue_declare(
            $queue,         #queue 
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
            $queue,                     #queue
            '',                         #consumer tag
            false,                      #no local
            false,                      #no ack
            false,                      #exclusive
            false,                      #no wait
            array($this, $callback)     #callback
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
    public function findAll(AMQPMessage $req)
    {
        $result = Users::all();

        if (count($result) == 0) {
            $response = [
                "status" => "error",
                "message" => "User not found",
                "data" => []
            ];
        }

        $response = [
            "status" => "success",
            "message" => "",
            "data" => $result
        ];

        $msg = new AMQPMessage(
            json_encode($response),
            array('correlation_id' => $req->get('correlation_id'))
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

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function findByNameOrUsername(AMQPMessage $req)
    {
        $data = json_decode($req->body);
        $result = Users::findByNameOrUsername($data->query);

        if (count($result) == 0) {
            $response = [
                "status" => "warning",
                "message" => "No records found",
                "data" => []
            ];
        }

        $response = [
            "status" => "success",
            "message" => "",
            "data" => $result
        ];

        $msg = new AMQPMessage(
            json_encode($response),
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

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function findById(AMQPMessage $req)
    {
        $data = json_decode($req->body);
        $result = Users::find($data->query);

        if (count($result) == 0) {
            $response = [
                "status" => "error",
                "message" => "User not found",
                "data" => []
            ];
        }

        $response = [
            "status" => "success",
            "message" => "",
            "data" => $result
        ];

        $msg = new AMQPMessage(
            json_encode($response),
            array('correlation_id' => $req->get('correlation_id'))
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

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function create(AMQPMessage $req)
    {
        $data = json_decode($req->body);
        $model = new Users();
        $response = $model->register($data);

        $msg = new AMQPMessage(
            json_encode($response),
            array('correlation_id' => $req->get('correlation_id'))
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

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function update(AMQPMessage $req)
    {
        $data = json_decode($req->body);
        $model = new Users();
        $response = $model->edit($data, $data->id);

        $msg = new AMQPMessage(
            json_encode($response),
            array('correlation_id' => $req->get('correlation_id'))
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

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function destroy(AMQPMessage $req)
    {
        $data = json_decode($req->body);
        $user = Users::find($data->query);

        if (!$user) {
            $response = [
                "message" => "User not found",
                "status" => "error",
                "data" => []
            ];
        }

        $user->delete();

        return $response = [
            "status" => "success",
            "message" => "",
            "data" => []
        ];

        $msg = new AMQPMessage(
            json_encode($response),
            array('correlation_id' => $req->get('correlation_id'))
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

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function login(AMQPMessage $req)
    {
        $data = json_decode($req->body);
        $model = new Users();
        $response = $model->login($data->username, $data->password);

        $msg = new AMQPMessage(
            json_encode($response),
            array('correlation_id' => $req->get('correlation_id'))
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
}
