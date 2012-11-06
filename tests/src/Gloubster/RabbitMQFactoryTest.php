<?php

namespace Gloubster;

use Gloubster\Configuration;

class RabbitMQFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $configuration;

    public function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration(file_get_contents(__DIR__ . '/../../resources/config.tests.json'));
    }

    /**
     * @covers Gloubster\RabbitMQFactory::createConnection
     */
    public function testCreateConnectedConnection()
    {
        $connection = RabbitMQFactory::createConnection($this->configuration);
        $this->assertInstanceOf('PhpAmqpLib\Connection\AMQPConnection', $connection);
    }
}
