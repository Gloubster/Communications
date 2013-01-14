<?php

namespace Gloubster\Tests\Message\Job;

use Gloubster\Message\Job\Factory;

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
     * @covers Gloubster\Message\Job\AbstractJob::create
     */
    abstract public function testCreateJob();

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setError
     * @covers Gloubster\Message\Job\AbstractJob::isOnError
     */
    public function testSetError()
    {
        $this->assertFalse($this->object->isOnError());
        $this->object->setError(true);
        $this->assertTrue($this->object->isOnError());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setErrorMessage
     * @covers Gloubster\Message\Job\AbstractJob::getErrorMessage
     */
    public function testSetErrorMessage()
    {
        $data = 'Jean Rochefort';
        $this->assertEquals('', $this->object->getErrorMessage());
        $this->object->setErrorMessage($data);
        $this->assertEquals($data, $this->object->getErrorMessage());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setReceipts
     * @covers Gloubster\Message\Job\AbstractJob::getReceipts
     */
    public function testSetReceipts()
    {
        $receipt = $this->getMockBuilder('Gloubster\\Receipt\\ReceiptInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $receipts = array($receipt);
        $this->object->setReceipts($receipts);
        $this->assertEquals($receipts, $this->object->getReceipts());

        $receipts = array();
        $this->object->setReceipts($receipts);
        $this->assertEquals($receipts, $this->object->getReceipts());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setReceipts
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testSetWrongReceipts()
    {
        $delivery = $this->getMockBuilder('Gloubster\\Delivery\\DeliveryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $receipts = array($delivery);
        $this->object->setReceipts($receipts);
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::addReceipt
     * @covers Gloubster\Message\Job\AbstractJob::getReceipts
     */
    public function testAddReceipt()
    {
        $this->object->setReceipts(array());
        $this->assertCount(0, $this->object->getReceipts());

        $receipt = $this->getMockBuilder('Gloubster\\Receipt\\ReceiptInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object->addReceipt($receipt);
        $this->assertEquals(array($receipt), $this->object->getReceipts());

        $this->object->addReceipt($receipt);
        $this->assertEquals(array($receipt, $receipt), $this->object->getReceipts());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getBeginning
     */
    public function testGetBeginning()
    {
        $this->assertInternalType('float', $this->object->getBeginning());
        $this->assertLessThan(microtime(true), $this->object->getBeginning());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getEnd
     * @covers Gloubster\Message\Job\AbstractJob::setEnd
     */
    public function testGetEnd()
    {
        $this->assertNull($this->object->getEnd());
        $this->object->setEnd(microtime(true));
        $this->assertInternalType('float', $this->object->getEnd());
        $this->assertLessThanOrEqual((float) (string)microtime(true), $this->object->getEnd());
        $this->assertLessthanOrEqual($this->object->getEnd(), $this->object->getBeginning());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setProcessDuration
     * @covers Gloubster\Message\Job\AbstractJob::getProcessDuration
     */
    public function testSetProcessDuration()
    {
        $this->assertNull($this->object->getProcessDuration());
        $this->object->setProcessDuration(0.12345678);
        $this->assertEquals(0.12345678, $this->object->getProcessDuration());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setDeliveryDuration
     * @covers Gloubster\Message\Job\AbstractJob::getDeliveryDuration
     */
    public function testSetDeliveryDuration()
    {
        $this->assertNull($this->object->getDeliveryDuration());
        $this->object->setDeliveryDuration(0.987654321);
        $this->assertEquals(0.987654321, $this->object->getDeliveryDuration());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setWorkerId
     * @covers Gloubster\Message\Job\AbstractJob::getWorkerId
     */
    public function testSetWorkerId()
    {
        $this->assertNull($this->object->getWorkerId());
        $this->object->setWorkerId('Jean Rochefort');
        $this->assertEquals('Jean Rochefort', $this->object->getWorkerId());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::requireReceipt
     */
    public function testRequireReceipt()
    {
        $this->assertFalse($this->object->requireReceipt());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::requireReceipt
     */
    public function testRequireReceiptMustReturnTrueWhenThereAreReceipts()
    {
        $receipt = $this->getMockBuilder('Gloubster\Receipt\ReceiptInterface')->getMock();
        $this->object->addReceipt($receipt);

        $this->assertTrue($this->object->requireReceipt());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getDelivery
     */
    public function testGetDelivery()
    {
        $this->assertInstanceOf('Gloubster\\Delivery\\DeliveryInterface', $this->object->getDelivery());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::toJson
     * @covers Gloubster\Message\Job\AbstractJob::fromJson
     */
    public function testJsonEncodeDecode()
    {
        $this->assertEquals($this->object, Factory::fromJson($this->object->toJson()));
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::toJson
     */
    public function testSerializeJsonIsString()
    {
        $this->assertInternalType('string', $this->object->toJson());
        $this->assertInternalType('array', json_decode($this->object->toJson(), true));
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::toJson
     * @covers Gloubster\Message\Job\AbstractJob::fromJson
     */
    public function testSerializeWithData()
    {
        $this->object->setError(true);
        $this->object->setEnd(microtime(true));
        $this->object->setProcessDuration(0.12345678);
        $this->object->setDeliveryDuration(0.987654321);
        $this->object->setWorkerId('Jean Rochefort');

        $this->assertEquals($this->object, Factory::fromJson($this->object->toJson()));
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::toJson
     * @covers Gloubster\Message\Job\AbstractJob::fromJson
     */
    public function testSerializeWithNoData()
    {
        $this->assertEquals($this->object, Factory::fromJson($this->object->toJson()));
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getParameters
     * @covers Gloubster\Message\Job\AbstractJob::addParameter
     */
    public function testGetParameters()
    {
        $this->assertInternalType('array', $this->object->getParameters());
        $this->object->setParameters(array('bidou'=>'boudou'));
        $this->assertEquals(array('bidou'=>'boudou'), $this->object->getParameters());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getParameter
     * @covers Gloubster\Message\Job\AbstractJob::addParameter
     * @covers Gloubster\Message\Job\AbstractJob::hasParameter
     * @covers Gloubster\Message\Job\AbstractJob::removeParameter
     */
    public function testGetParameter()
    {
        $this->assertFalse($this->object->hasParameter('bidou'));
        $this->object->addParameter('bidou', 'boudou');
        $this->assertTrue($this->object->hasParameter('bidou'));
        $this->assertEquals('boudou', $this->object->getParameter('bidou'));
        $this->object->addParameter('bidou', 'doudou');
        $this->assertEquals('doudou', $this->object->getParameter('bidou'));
        $this->object->removeParameter('bidou');
        $this->assertFalse($this->object->hasParameter('bidou'));
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getParameter
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testGetParameterMustThrowAnxceptionOnInvalidParameter()
    {
        $this->object->getParameter('bidou');
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::removeParameter
     * @expectedException Gloubster\Exception\InvalidArgumentException
     */
    public function testRemoveParameterMustThrowAnxceptionOnInvalidParameter()
    {
        $this->object->removeParameter('bidou');
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getMandatoryParameters
     */
    public function testGetMandatoryParameters()
    {
        $this->assertInternalType('array', $this->object->getMandatoryParameters());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::isOk
     */
    public function testIsOk()
    {
        $this->assertInternalType('boolean', $this->object->isOk());
        $this->assertTrue($this->object->isOk());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::isOk
     */
    public function testIsOkWithException()
    {
        $this->assertInternalType('boolean', $this->object->isOk());
        $this->assertTrue($this->object->isOk(true));
    }

    /**
     * @dataProvider getWrongJobs
     * @covers Gloubster\Message\Job\AbstractJob::isOk
     */
    public function testIsNotOk($object)
    {
        $this->assertInternalType('boolean', $object->isOk());
        $this->assertFalse($object->isOk());
    }

    /**
     * @dataProvider getWrongJobs
     * @expectedException Gloubster\Exception\RuntimeException
     * @covers Gloubster\Message\Job\AbstractJob::isOk
     */
    public function testIsNotOkWithException($object)
    {
        $object->isOk(true);
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getRoutingKey
     */
    public function testGetRoutingKey()
    {
        $this->assertInternalType('string', $this->object->getRoutingKey());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::getExchangeName
     */
    public function testGetExchangeName()
    {
        $this->assertInternalType('string', $this->object->getExchangeName());
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::toJson
     */
    public function testValidJson()
    {
        $this->assertInternalType('array', json_decode($this->object->toJson(), true));
    }

    /**
     * @covers Gloubster\Message\Job\AbstractJob::setResult
     * @covers Gloubster\Message\Job\AbstractJob::getResult
     */
    public function testGetSetResult()
    {
        $result = array('sha245' => '65647576890abc');
        $this->object->setResult($result);
        $this->assertEquals($result, $this->object->getResult());

        $data = json_decode($this->object->toJson(), true);

        $this->assertEquals($result, $data['result']);
    }
}

