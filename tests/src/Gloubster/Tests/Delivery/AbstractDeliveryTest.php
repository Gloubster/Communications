<?php

namespace Gloubster\Tests\Delivery;

use Gloubster\Delivery\Factory;

/**
 * @covers Gloubster\Delivery\DeliveryInterface
 */
abstract class AbstractDeliveryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return DeliveryInterface
     */
    abstract public function getDelivery();

    abstract public function readTarget();

    public function testGetName()
    {
        $this->assertInternalType('string', $this->getDelivery()->getName());
        $this->assertGreaterThan(0, strlen($this->getDelivery()->getName()));
    }

    /**
     * @expectedException Gloubster\Exception\LogicException
     */
    public function testGetIdBeforeDeliver()
    {
        $this->getDelivery()->getId();
    }

    public function testGetIdAfterDeliverBinary()
    {
        $delivery = $this->getDelivery();
        $expectedId = $delivery->deliverBinary('Jean Rochefort');
        $this->assertTrue(is_scalar($delivery->getId()));
        $this->assertEquals($expectedId, $delivery->getId());
    }

    public function testGetIdAfterDeliverFile()
    {
        $file = tempnam(sys_get_temp_dir(), 'testfile');
        file_put_contents($file, 'Jean-Rochefort');
        $delivery = $this->getDelivery();
        $expectedId = $delivery->deliverFile($file);
        $this->assertTrue(is_scalar($delivery->getId()));
        $this->assertEquals($expectedId, $delivery->getId());
        unlink($file);
    }

    public function testFetch()
    {
        $data = 'Jean Rochefort';
        $delivery = $this->getDelivery();
        $id = $delivery->deliverBinary($data);
        $this->assertEquals($data, $delivery->fetch($id));
    }

    public function testDeliverBinary()
    {
        $data = 'random' . mt_rand();
        $id = $this->getDelivery()->deliverBinary($data);
        $this->assertTrue(is_scalar($id));
        $this->assertEquals($data, $this->readTarget());
    }

    public function testDeliverFile()
    {
        $file = tempnam(sys_get_temp_dir(), 'testfile');

        $data = 'random' . mt_rand();
        file_put_contents($file, $data);
        $id = $this->getDelivery()->deliverFile($file);
        $this->assertTrue(is_scalar($id));
        $this->assertEquals($data, $this->readTarget());
    }

    public function testToArray()
    {
        $delivery = $this->getDelivery();
        $this->assertEquals($delivery, Factory::fromArray($delivery->toArray()));
    }

    public function testFromArrayIsAnArray()
    {
        $this->assertInternalType('array', $this->getDelivery()->toArray());
    }
}
