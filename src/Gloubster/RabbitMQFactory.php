<?php

namespace Gloubster;

class RabbitMQFactory
{
    public static function createConnection(Configuration $conf)
    {
        return new \AMQPConnection(array(
            'host'     => $conf['server']['host'],
            'port'     => $conf['server']['port'],
            'login'    => $conf['server']['user'],
            'password' => $conf['server']['password'],
            'vhost'    => $conf['server']['vhost'],
        ));
    }

    public static function createChannel(\AMQPConnection $connection)
    {
        return new \AMQPChannel($connection);
    }

    public static function getExchange(\AMQPChannel $channel, $name)
    {
        $exchange = new \AMQPExchange($channel);
        $exchange->setName($name);

        return $exchange;
    }

    public static function getQueue(\AMQPChannel $channel, $name)
    {
        $queue = new \AMQPQueue($channel);
        $queue->setName($name);
    }

    public static function createExchange(\AMQPChannel $channel, $name, $type)
    {
        $exchange = new \AMQPExchange($channel);
        $exchange->setName($name);
        $exchange->setType($type);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare();

        return $exchange;
    }

    public static function createQueue(\AMQPChannel $channel, $name, array $binding)
    {
        $queue = new \AMQPQueue($channel);
        $queue->setName($name);
        $queue->setFlags(AMQP_DURABLE);

        list($exchangeName, $routingKey) = $binding;
        $queue->bind($exchangeName, $routingKey);

        $queue->declare();

        return $queue;
    }
}
