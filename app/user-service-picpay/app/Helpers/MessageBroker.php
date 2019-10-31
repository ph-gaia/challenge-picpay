<?php

namespace App\Helpers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Models\Users;

class MessageBroker
{

    private $connection;
    private $channel;
    private $response;
    private $corr_id;

    private $host = "chimpanzee.rmq.cloudamqp.com";
    private $port = 5672;
    private $user = "ldqoqigt";
    private $password = "sQcB6mPNv0qsLdDQg4lQF7U3LL9TnI0K";
    private $vhost = "ldqoqigt";

    private function connect()
    {
        try {
            $this->connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password, $this->vhost);
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * Method responsible only for sending a message
     *
     * @param $queue
     * @param $message
     */
    public function publish($queue, $message)
    {
        $this->connect();

        $this->channel->queue_declare(
            $queue,             #queue - Queue names may be up to 255 bytes of UTF-8 characters
            false,              #passive - can use this to check whether an exchange exists without modifying the server state
            true,               #durable, make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            false,              #exclusive - used by only one connection and the queue will be deleted when that connection closes
            false               #auto delete - queue is deleted when last consumer unsubscribes
        );

        $properties = array(
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        );
        $msg = new AMQPMessage(
            json_encode($message),
            $properties
        );

        $this->channel->basic_publish(
            $msg,               #message
            '',                 #exchange
            $queue              #routing key (queue)
        );

        $this->closeConnection();
    }

    /**
     * Method responsible for listening to events and processing
     *
     * @param $queue
     * @param $callback
     */
    public function listen($queue, $callback)
    {
        $this->connect();

        $this->channel->queue_declare(
            $queue,                 #queue - Queue names may be up to 255 bytes of UTF-8 characters
            false,                  #passive - can use this to check whether an exchange exists without modifying the server state
            true,                   #durable, make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            false,                  #exclusive - used by only one connection and the queue will be deleted when that connection closes
            false                   #auto delete - queue is deleted when last consumer unsubscribes
        );

        $this->channel->basic_qos(
            null,                   #prefetch size - prefetch window size in octets, null meaning "no specific limit"
            1,                      #prefetch count - prefetch window in terms of whole messages
            null                    #global - global=null to mean that the QoS settings should apply per-consumer, global=true to mean that the QoS settings should apply per-channel
        );

        $this->channel->basic_consume(
            $queue,                 #queue
            '',                     #consumer tag - Identifier for the consumer, valid within the current channel. just string
            false,                  #no local - TRUE: the server will not send messages to the connection that published them
            false,                  #no ack, false - acks turned on, true - off.  send a proper acknowledgment from the worker, once we're done with a task
            false,                  #exclusive - queues may only be accessed by the current connection
            false,                  #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            array($this, $callback) #callback
        );

        while (count($this->channel->callbacks)) {
            $this->log->addInfo('Waiting for messages');
            $this->channel->wait();
        }

        $this->closeConnection();
    }

    /**
     * Method responsible for sending a message and receiving a return
     * Remote Procedure Call - RPC
     */
    public function SendAndListen($queue, $message, $callback = 'onResponse')
    {
        $this->connect();

        list($callback_queue,,) = $this->channel->queue_declare(
            "",         #queue
            false,      #passive
            false,      #durable
            true,       #exclusive
            false       #auto delete
        );

        $this->channel->basic_consume(
            $callback_queue,                #queue
            '',                             #consumer tag
            false,                          #no local
            false,                          #no ack
            false,                          #exclusive
            false,                          #no wait
            array($this, $callback)         #callback
        );

        $this->response = null;

        $this->corr_id = uniqid();
        $jsonCredentials = json_encode($message);

        $msg = new AMQPMessage(
            $jsonCredentials,
            array('correlation_id' => $this->corr_id)
        );

        $this->channel->basic_publish(
            $msg,           #message
            '',             #exchange
            $queue          #routing key
        );

        while (!$this->response) {
            $this->channel->wait();
        }

        $this->closeConnection();

        return $this->response;
    }

    /**
     * @param AMQPMessage $rep
     */
    public function onResponse(AMQPMessage $rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function listenAndSend($queue, $callback = 'process')
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
    public function process(AMQPMessage $req)
    {
        $credentials = json_decode($req->body);
        $authResult = Users::login($credentials);

        $msg = new AMQPMessage(
            json_encode(array('data' => $authResult)),              #message
            array('correlation_id' => $req->get('correlation_id'))  #options
        );

        $req->delivery_info['channel']->basic_publish(
            $msg,                   #message
            '',                     #exchange
            $req->get('reply_to')   #routing key
        );

        $req->delivery_info['channel']->basic_ack(
            $req->delivery_info['delivery_tag'] #delivery tag
        );
    }
}
