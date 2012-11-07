<?php

namespace Gloubster\Delivery;

/**
 * @covers Gloubster\Delivery\FileSystem
 */
class FileSystemTest extends AbstractDeliveryTest
{
    protected $target;

    public function getDelivery()
    {
        $this->target = tempnam(sys_get_temp_dir(), 'test_filesystem_delivery');
        return new FileSystem($this->target);
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
}
