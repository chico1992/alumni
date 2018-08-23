<?php

namespace App\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageSender
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
    }
    
    public function pushToQueue($message, $queue)
    {
        $this->channel->queue_declare($queue,false,false,false,false);
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg,'',$queue);
        $this->channel->close();
        $this->connection->close();
        return true;
    }

}