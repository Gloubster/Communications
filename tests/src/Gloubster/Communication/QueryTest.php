<?php

namespace Gloubster\Communication;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    protected $redisConfiguration;

    public function setUp()
    {
        $this->redisConfiguration = array('host' => 'localhost', 'port' => 6379);
    }

    /**
     * @covers Gloubster\Communication\Query::getUuid
     * @covers Gloubster\Communication\Query::getFile
     * @covers Gloubster\Communication\Query::getParameters
     * @covers Gloubster\Communication\Query::getDelivery
     * @covers Gloubster\Communication\Query::__construct
     * @todo Implement testGetUuid().
     */
    public function testGetters()
    {
        $uuid = 'randomId';
        $file = 'http://file.jpg';
        $deliveryName = 'RedisStore';
        $deliverySignature = md5(json_encode(array_values($this->redisConfiguration)));
        $parameters = array('width'  => 320, 'height' => 240);

        $query = new Query($uuid, $file, $deliveryName, $deliverySignature, $parameters);

        $this->assertEquals($uuid, $query->getUuid());
        $this->assertEquals($file, $query->getFile());
        $this->assertEquals($parameters, $query->getParameters());

        $delivery = $query->getDelivery(new \Gloubster\Delivery\Factory, $this->redisConfiguration);

        $this->assertEquals($deliveryName, $delivery->getName());
        $this->assertEquals($deliverySignature, $delivery->getSignature());
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testWrongSignature()
    {
        $query = new Query('randomId', 'http://file.jpg', 'RedisStore', 'randomwords');
        $query->getDelivery(new \Gloubster\Delivery\Factory, $this->redisConfiguration);
    }

    /**
     * @covers Gloubster\Communication\Query::serialize
     * @covers Gloubster\Communication\Query::unserialize
     */
    public function testSerialize()
    {
        $query = new Query('randomId', 'http://file.jpg', 'RedisStore', md5(json_encode(array_values($this->redisConfiguration))));
        $query2 = unserialize(serialize($query));
        $this->assertEquals($query, $query2);
    }

    /**
     * @covers Gloubster\Communication\Query::unserialize
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testSerializingCorruption()
    {
        $query = $this->getMock('Gloubster\Communication\Query', array('serialize'), array('randomId', 'http://file.jpg', 'RedisStore', 'randomwords'));

        $query->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue('prout !')
        );

        unserialize(serialize($query));
    }
}
