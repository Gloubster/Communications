<?php

namespace Gloubster\Job;

abstract class AbstractJobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JobInterface
     */
    protected $object;

    public function setUp()
    {
        parent::setUp();
        $this->object = $this->getJob();
    }

    abstract public function getJob();

    /**
     * @covers Gloubster\Job\AbstractJob::setError
     * @covers Gloubster\Job\AbstractJob::isOnError
     */
    public function testSetError()
    {
        $this->assertFalse($this->object->isOnError());
        $this->object->setError(true);
        $this->assertTrue($this->object->isOnError());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::getBeginning
     */
    public function testGetBeginning()
    {
        $this->assertInternalType('float', $this->object->getBeginning());
        $this->assertLessThan(microtime(true), $this->object->getBeginning());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::getEnd
     * @covers Gloubster\Job\AbstractJob::setEnd
     */
    public function testGetEnd()
    {
        $this->assertNull($this->object->getEnd());
        $this->object->setEnd(microtime(true));
        $this->assertInternalType('float', $this->object->getEnd());
        $this->assertLessThan(microtime(true), $this->object->getEnd());
        $this->assertLessthan($this->object->getEnd(), $this->object->getBeginning());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::setProcessDuration
     * @covers Gloubster\Job\AbstractJob::getProcessDuration
     */
    public function testSetProcessDuration()
    {
        $this->assertNull($this->object->getProcessDuration());
        $this->object->setProcessDuration(0.12345678);
        $this->assertEquals(0.12345678, $this->object->getProcessDuration());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::setDeliveryDuration
     * @covers Gloubster\Job\AbstractJob::getDeliveryDuration
     */
    public function testSetDeliveryDuration()
    {
        $this->assertNull($this->object->getDeliveryDuration());
        $this->object->setDeliveryDuration(0.987654321);
        $this->assertEquals(0.987654321, $this->object->getDeliveryDuration());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::setWorkerId
     * @covers Gloubster\Job\AbstractJob::getWorkerId
     */
    public function testSetWorkerId()
    {
        $this->assertNull($this->object->getWorkerId());
        $this->object->setWorkerId('Jean Rochefort');
        $this->assertEquals('Jean Rochefort', $this->object->getWorkerId());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::requireReceipt
     */
    public function testRequireReceipt()
    {
        $this->assertInternalType('boolean', $this->object->requireReceipt());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::getDelivery
     */
    public function testGetDelivery()
    {
        $this->assertInstanceOf('Gloubster\\Delivery\\DeliveryInterface', $this->object->getDelivery());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::serialize
     * @covers Gloubster\Job\AbstractJob::unserialize
     */
    public function testSerialize()
    {
        $this->assertEquals($this->object, unserialize(serialize($this->object)));
    }

    /**
     * @covers Gloubster\Job\AbstractJob::serialize
     */
    public function testSerializeIsString()
    {
        $this->assertInternalType('string', serialize($this->object));
    }

    /**
     * @covers Gloubster\Job\AbstractJob::serialize
     * @covers Gloubster\Job\AbstractJob::unserialize
     */
    public function testSerializeWithData()
    {
        $this->object->setError(true);
        $this->object->setEnd(microtime(true));
        $this->object->setProcessDuration(0.12345678);
        $this->object->setDeliveryDuration(0.987654321);
        $this->object->setWorkerId('Jean Rochefort');

        $this->assertEquals($this->object, unserialize(serialize($this->object)));
    }

    /**
     * @covers Gloubster\Job\AbstractJob::getParameters
     * @covers Gloubster\Job\AbstractJob::setParameters
     */
    public function testGetParameters()
    {
        $this->assertInternalType('array', $this->object->getParameters());
        $this->object->setParameters(array('bidou'=>'boudou'));
        $this->assertEquals(array('bidou'=>'boudou'), $this->object->getParameters());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::getMandatoryParameters
     */
    public function testGetMandatoryParameters()
    {
        $this->assertInternalType('array', $this->object->getMandatoryParameters());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::isOk
     */
    public function testIsOk()
    {
        $this->assertInternalType('boolean', $this->object->isOk());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::getRoutingKey
     */
    public function testGetRoutingKey()
    {
        $this->assertInternalType('string', $this->object->getRoutingKey());
    }

    /**
     * @covers Gloubster\Job\AbstractJob::getExchangeName
     */
    public function testGetExchangeName()
    {
        $this->assertInternalType('string', $this->object->getExchangeName());
    }
}
