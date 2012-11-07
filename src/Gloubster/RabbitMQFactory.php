<?php

namespace Gloubster;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;

class RabbitMQFactory
{

    public static function createConnection(Configuration $conf)
    {
        if (isset($conf['server']['ssl']) && $conf['server']['ssl']['enable']) {
            $connection = new AMQPSSLConnection(
                $conf['server']['host'],
                $conf['server']['port'],
                $conf['server']['user'],
                $conf['server']['password'],
                $conf['server']['vhost'],
                isset($conf['server']['ssl']['options']) ? $conf['server']['ssl']['options'] : array()
            );
        } else {
            $connection = new AMQPConnection(
                $conf['server']['host'],
                $conf['server']['port'],
                $conf['server']['user'],
                $conf['server']['password'],
                $conf['server']['vhost']
            );
        }

        register_shutdown_function(function(AMQPConnection $connection) {
            try {
                $connection->close();
            } catch (\Exception $e) {

            }
        }, $connection);

        return $connection;
    }
}
