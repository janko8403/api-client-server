<?php

namespace App\Classes;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Classes\SoapAdapter;
use App\Classes\GeneratePass;
use App\Classes\Services_JSON;
use Log;
use Web64\Colors\Facades\Colors;
use App\Models\{AssemblyOrder, AssemblyRanking, User};

class AMQLib {

    protected $host;
    protected $port;
    protected $user;
    protected $password;
    protected $soap;
    protected $connection;

    private const ASSEMBLY_ORDER_ACCEPTED = 'assembly-order-accepted';
    private const ASSEMBLY_ORDER_UPDATED = 'assembly-order-updated';

    public function __construct()
    {
        $this->generate = new GeneratePass;
        $this->soap = new SoapAdapter;
        $this->connection = new AMQPStreamConnection(
            $this->host = config('rabbitmq.connections.host'),
            $this->port = config('rabbitmq.connections.port'),
            $this->user = config('rabbitmq.connections.user'),
            $this->password = config('rabbitmq.connections.password')
        );
    }

    public function publish($content, $queue_name, $test = NULL)
    {
        $channel = $this->connection->channel();
        $msg = new AMQPMessage($content);
        $channel->basic_publish($msg, $queue_name);
        if($test !== NULL) {
            Colors::question('Sent queue: ' . $content);
            Colors::question('Created order');
            Colors::created('Test: OK');
        } else { 
            echo "[x] Sent queue: " . $content . "\n";
        }

        $channel->close();
        $this->connection->close();
    }

    public function publishTest()
    {
        $m = '{"content":"a:3:{s:2:\"id\";i:1;s:7:\"user_id\";i:2;s:4:\"date\";i:1657638718;}","metadata":{"__name__":"AssemblyOrders\\Job\\AssemblyOrderAccepted"}}';
        $channel = $this->connection->channel();
        $msg = new AMQPMessage($m);
        $channel->basic_publish($msg, self::ASSEMBLY_ORDER_ACCEPTED);
        Colors::question('[x] Sent: ' . $m);

        $channel->close();
        $this->connection->close();
    }

    public function consume()
    {
        $channel = $this->connection->channel();
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            $json = new Services_JSON();
            $output = $json->decode($msg->body);
            $content = unserialize($output->content);

            $user = $this->getUser($content['user_id']);
            $assemblyOrder = $this->getAssemblyOrder($content['id']);
            $date = $this->getDate($content['date']);
            Colors::created('[x] Content: ' . $assemblyOrder . ' - ' . $user . ' - ' . $date);
            // $this->soap->sendData($msg->body);
        };

        $channel->basic_consume(self::ASSEMBLY_ORDER_ACCEPTED, '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();
    }

    public function getUser($userId)
    {
       $user =  User::where('id', $userId)->first();
       return $user->referenceNumber;
    }

    public function getAssemblyOrder($id)
    {
        $assemblyOrder = AssemblyOrder::where('id', $id)->first();
        return $assemblyOrder->idMeasurementOrder;
    }

    public function getDate($date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }
}
