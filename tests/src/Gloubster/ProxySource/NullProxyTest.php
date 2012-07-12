<?php

namespace Gloubster\ProxySource;

class NullProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullProxy
     */
    protected $object;

    /**
     * @covers Gloubster\ProxySource\NullProxy
     */
    protected function setUp()
    {
        $this->object = new NullProxy;
    }

    /**
     * @covers Gloubster\ProxySource\AbstractProxySource::getDatas
     */
    public function testGetDatas()
    {
        $this->assertEquals(file_get_contents(__FILE__), $this->object->getDatas('file://' . __FILE__));
    }

    /**
     * @covers Gloubster\ProxySource\AbstractProxySource::setExpiration
     * @covers Gloubster\ProxySource\AbstractProxySource::getExpiration
     */
    public function testSetExpiration()
    {
        $this->object->setExpiration(200);
        $this->assertEquals(200, $this->object->getExpiration());
    }
}
