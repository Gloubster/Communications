<?php

namespace Gloubster\Tests\Delivery;

use Gloubster\Delivery\Filesystem;

/**
 * @covers Gloubster\Delivery\FileSystem
 */
class FileSystemTest extends AbstractDeliveryTest
{
    protected $target;

    public function getDelivery()
    {
        $this->target = tempnam(sys_get_temp_dir(), 'test_filesystem_delivery');
        return FileSystem::create($this->target);
    }

    protected function tearDown()
    {
        if (file_exists($this->target)) {
            unlink($this->target);
        }
    }

    public function readTarget()
    {
        return file_get_contents($this->target);
    }

    public function testGetSetTarget()
    {
        $target = '/path/to/target';
        $delivery = $this->getDelivery();
        $delivery->setTarget($target);
        $this->assertEquals($target, $delivery->getTarget());
    }

    public function testGetSetDelivered()
    {
        $delivered = true;
        $delivery = $this->getDelivery();
        $delivery->setDelivered($delivered);
        $this->assertEquals($delivered, $delivery->getDelivered());
    }

    public function testFilesystemFromArray()
    {
        $delivery = $this->getDelivery();
        $this->assertEquals($delivery, Filesystem::fromArray($delivery->toArray()));
    }
}
