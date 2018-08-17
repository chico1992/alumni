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
        $this->channel->queue_declare('messages',false,false,false,false);
    }

    public function sendMessage($message)
    {
        $this->channel->basic_publish(new AMQPMessage($message),'','messages');
        $this->channel->close();
        $this->connection->close();
        return 200;
    }

}