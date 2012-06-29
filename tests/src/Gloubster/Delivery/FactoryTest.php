<?php

namespace Gloubster\Delivery;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Gloubster\Delivery\Factory::build
     */
    public function testBuildRedis()
    {
        $this->getWorkingRedis();

        $redisStore = Factory::build('RedisStore', array('host' => 'localhost', 'port' => 6379));
        $this->assertInstanceOf('Gloubster\\Delivery\\RedisStore', $redisStore);
    }

    /**
     * @covers Gloubster\Delivery\Factory::build
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testBuildNonExistent()
    {
        Factory::build('WeDontKnowWhat', array());
    }

    protected function getWorkingRedis()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = new \Redis();
        $redis->connect('localhost', 6379);

        try {
            $pong = $redis->ping();
        } catch (\RedisException $e) {
            $pong = null;
        }

        if ('+PONG' !== $pong) {
            $this->markTestSkipped('Unable to reach Redis server');
        }

        return $redis;
    }
}
