<?php

namespace Gloubster\ProxySource;

class RedisTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Gloubster\ProxySource\ProxySourceInterface
     * @covers Gloubster\ProxySource\Redis::__construct
     * @covers Gloubster\ProxySource\Redis::getDatas
     * @covers Gloubster\ProxySource\Redis::fetch
     * @covers Gloubster\ProxySource\AbstractProxySource::getHash
     * @covers Gloubster\ProxySource\Redis::has
     */
    public function testGetDatasNotCached()
    {
        $redis = $this->getMock('\Redis');
        $redis->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));

        $object = new Redis($redis);

        $this->assertEquals(file_get_contents(__FILE__), $object->getDatas('file://' . __FILE__));
    }

    /**
     * @covers Gloubster\ProxySource\Redis::__construct
     * @covers Gloubster\ProxySource\Redis::getDatas
     * @covers Gloubster\ProxySource\Redis::has
     * @covers Gloubster\ProxySource\Redis::Store
     * @covers Gloubster\ProxySource\Redis::fetch
     * @covers Gloubster\ProxySource\AbstractProxySource::getHash
     * @covers Gloubster\ProxySource\Redis::get
     */
    public function testGetDatasCached()
    {
        $redis = $this->getMock('\Redis');

        $store = $Rkey = null;
        $has = false;

        $redis->expects($this->any())
            ->method('exists')
            ->will($this->returnCallback(function() use (&$has) {
                        $ret = $has;
                        $has = true;

                        return $ret;
                    }));

        $redis->expects($this->once())
            ->method('setex')
            ->will($this->returnCallback(function($key, $expiration, $datas) use (&$Rkey, &$store) {
                        $store = $datas;
                        $Rkey = $key;
                    }));

        $redis->expects($this->once())
            ->method('get')
            ->will($this->returnCallback(function($key) use (&$Rkey, &$store) {
                        if ($key === $Rkey) {
                            return $store;
                        }
                    }));

        $object = new Redis($redis);

        $this->assertEquals(file_get_contents(__FILE__), $object->getDatas('file://' . __FILE__));
        $this->assertEquals(file_get_contents(__FILE__), $object->getDatas('file://' . __FILE__));
    }

    /**
     * @covers Gloubster\ProxySource\AbstractProxySource::setExpiration
     * @covers Gloubster\ProxySource\AbstractProxySource::getExpiration
     */
    public function testSetExpiration()
    {
        $redis = $this->getMock('\Redis');
        $object = new Redis($redis);

        $object->setExpiration(200);
        $this->assertEquals(200, $object->getExpiration());
    }
}
