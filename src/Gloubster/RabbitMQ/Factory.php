<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\RabbitMQ;

use Gloubster\Configuration as MainConfiguration;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;

class Factory
{
    public static function createAMQPLibConnection(MainConfiguration $conf)
    {
        if (isset($conf['server']['ssl']) && isset($conf['server']['ssl']['enable']) && true === $conf['server']['ssl']['enable']) {
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
