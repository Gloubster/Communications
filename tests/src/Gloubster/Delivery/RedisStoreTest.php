<?php

namespace Gloubster\Delivery;

class RedisStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RedisStore
     */
    protected $object;

    /**
     * @covers Gloubster\Delivery\RedisStore::__destruct
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::__construct
     * @covers Gloubster\Delivery\RedisStore::getName
     * @covers Gloubster\Delivery\RedisStore::getSignature
     */
    public function testBasic()
    {
        $redis = new \Redis();
        $signature = 'signature';
        $this->object = new RedisStore($redis, $signature);
        $this->assertEquals('RedisStore', $this->object->getName());
        $this->assertEquals($signature, $this->object->getSignature());
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::deliver
     */
    public function testDeliver()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = $this->getMock('\Redis');
        $result = $this->getResultMock();

        $result->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue(json_encode(array()))
        );
        $redis->expects($this->once())
            ->method('set');

        $this->object = new RedisStore($redis, 'signature');
        $this->object->deliver('test', $result);
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::deliver
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testDeliverDoesNotWork()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = $this->getMock('\Redis');
        $result = $this->getResultMock();

        $redis->expects($this->once())
            ->method('set')
            ->will($this->returnValue(false));

        $this->object = new RedisStore($redis, 'signature');
        $this->object->deliver('test', $result);
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::deliver
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testDeliverRedisThrowsException()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = $this->getMock('\Redis');
        $result = $this->getResultMock();

        $redis->expects($this->once())
            ->method('set')
            ->will($this->throwException(new \RedisException()));

        $this->object = new RedisStore($redis, 'signature');
        $this->object->deliver('test', $result);
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::retrieve
     */
    public function testRetrieve()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = $this->getMock('\Redis');
        $redis->expects($this->once())
            ->method('get');

        $this->object = new RedisStore($redis, 'signature');
        $this->object->retrieve('test');
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::retrieve
     * @covers Gloubster\Delivery\Exception\ItemDoesNotExistsException
     * @expectedException Gloubster\Delivery\Exception\ItemDoesNotExistsException
     */
    public function testRetrieveDoesNotWork()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = $this->getMock('\Redis');
        $redis->expects($this->once())
            ->method('get')
            ->will($this->returnValue(false));

        $this->object = new RedisStore($redis, 'signature');
        $this->object->retrieve('test');
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::retrieve
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testRetrieveCorruptedDatas()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = $this->getMock('\Redis');
        $result = $this->getResultMock();

        $redis->expects($this->once())
            ->method('get')
            ->will($this->returnValue(array()));

        $this->object = new RedisStore($redis, 'signature');
        $this->object->retrieve('test');
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::retrieve
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testRetrieveThrowException()
    {
        if (false === class_exists('\\Redis')) {
            $this->markTestSkipped('This test requires Redis extension');
        }

        $redis = $this->getMock('\Redis');
        $redis->expects($this->once())
            ->method('get')
            ->will($this->throwException(new \RedisException));

        $this->object = new RedisStore($redis, 'signature');
        $this->object->retrieve('test');
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::retrieve
     * @covers Gloubster\Delivery\RedisStore::deliver
     */
    public function testFunctionnalRedisTest()
    {
        $redis = $this->getWorkingRedis();

        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');

        $result = new \Gloubster\Job\Result($jobHandle, $uuid, $workload, $binaryData, $duration, $infos);

        $this->object = new RedisStore($redis, 'signature');
        $this->object->deliver('test', $result);
        $data = $this->object->retrieve('test');
        $this->assertEquals($result, $data);
    }

    /**
     * @dataProvider getWrongConfs
     * @covers Gloubster\Delivery\RedisStore::build
     * @covers Gloubster\Exception\InvalidArgumentException
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testBuildFailing($conf)
    {
        RedisStore::build($conf);
    }

    public function getWrongConfs()
    {
        return array(
            array(array('host' => 'localhost')),
            array(array('port' => 6379)),
            array(array()),
        );
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::build
     * @covers Gloubster\Exception\RuntimeException
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testBuildWrongHostPort()
    {
        RedisStore::build(array('host' => 'localhost', 'port' => '80'));
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::build
     */
    public function testBuild()
    {
        $this->getWorkingRedis();
        RedisStore::build(array('host' => 'localhost', 'port' => 6379));
    }

    /**
     * @covers Gloubster\Delivery\RedisStore::build
     */
    public function testBuildsEquals()
    {
        $this->getWorkingRedis();
        $build1 = RedisStore::build(array('host'  => 'localhost', 'port'  => 6379));
        $build2 = RedisStore::build(array('host' => 'localhost', 'port' => '6379'));

        $this->assertEquals($build2->getSignature(), $build1->getSignature());
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

    protected function getResultMock()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');

        $result = $this->getMock('\Gloubster\Job\Result', array(), array($jobHandle, $uuid, $workload, $binaryData, $duration, $infos));

        return $result;
    }
}
