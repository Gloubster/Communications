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

        $configuration = $this->getMock('\\Gloubster\\Configuration', array('offsetGet'), array(), '', false);

        $configuration->expects($this->any())
            ->method('offsetGet')
            ->will($this->returnCallback(
                    function ($key) {
                        return array(
                            'name'          => 'RedisStore',
                            'configuration' => array('host' => 'localhost', 'port' => 6379)
                        );
                    }
                ));


        $redisStore = Factory::build($configuration);
        $this->assertInstanceOf('Gloubster\\Delivery\\RedisStore', $redisStore);
    }

    /**
     * @covers Gloubster\Delivery\Factory::build
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testBuildNonExistent()
    {
        $configuration = $this->getMock('\\Gloubster\\Configuration', array('offsetGet'), array(), '', false);

        $configuration->expects($this->any())
            ->method('offsetGet')
            ->will($this->returnCallback(
                    function ($key) {
                        return array(
                            'name'          => 'WeDontKnowWhat',
                            'configuration' => array()
                        );
                    }
                ));

        Factory::build($configuration);
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
