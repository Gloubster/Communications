<?php

namespace Gloubster\Delivery;

abstract class AbstractDeliveryTest extends \PHPUnit_Framework_TestCase
{

    abstract public function getDelivery();

    abstract public function readTarget();

    public function testDeliverBinary()
    {
        $data = 'random' . mt_rand();
        $this->getDelivery()->deliverBinary($data);

        $this->assertEquals($data, $this->readTarget());
    }

    public function testDeliverFile()
    {
        $file = tempnam(sys_get_temp_dir(), 'testfile');

        $data = 'random' . mt_rand();
        file_put_contents($file, $data);
        $this->getDelivery()->deliverFile($file);

        $this->assertEquals($data, $this->readTarget());
    }

    public function testSerialize()
    {
        $delivery = $this->getDelivery();
        $this->assertEquals($delivery, unserialize(serialize($delivery)));
    }
}
