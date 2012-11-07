<?php

namespace Gloubster\Job;

use Gloubster\Delivery\FileSystem;

/**
 * @covers Gloubster\Job\AbstractJob
 * @covers Gloubster\Job\ImageJob
 */
class ImageJobTest extends AbstractJobTest
{

    public function getJob()
    {
        return new ImageJob(__DIR__ . '/../../testfiles/photo02.JPG', new FileSystem(__DIR__ . '/../../target.jpg'));
    }

    /**
     * @covers Gloubster\Job\ImageJob::isOk
     */
    public function testIsImageJobOk()
    {
        $object = $this->getJob();
        $this->assertFalse($object->isOk());
        $object->setParameters(array('format'=>'jpg'));
        $this->assertTrue($object->isOk());
    }

    /**
     * @covers Gloubster\Job\ImageJob::isOk
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testIsImageJobNotOkWithException()
    {
        $this->getJob()->isOk(true);
    }

    /**
     * @covers Gloubster\Job\ImageJob::isOk
     */
    public function testIsImageJobOkWithException()
    {
        $object = $this->getJob();
        $object->setParameters(array('format'=>'jpg'));
        $this->assertTrue($object->isOk(true));
    }

    /**
     * @covers Gloubster\Job\ImageJob::getSource
     */
    public function testGetSource()
    {
        $this->assertTrue(file_exists($this->getJob()->getSource()));
    }
}
