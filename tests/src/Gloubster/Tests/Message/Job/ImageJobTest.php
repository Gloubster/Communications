<?php

namespace Gloubster\Tests\Message\Job;

use Gloubster\Message\Job\ImageJob;
use Gloubster\Delivery\FileSystem;

/**
 * @covers Gloubster\Message\Job\AbstractJob
 * @covers Gloubster\Message\Job\ImageJob
 */
class ImageJobTest extends AbstractJobTest
{

    public function getJob()
    {
        $job = new ImageJob();

        return $job->setSource(__DIR__ . '/../../../../testfiles/photo02.JPG')
            ->setDelivery(new FileSystem(__DIR__ . '/../../../../target.jpg'));
    }

    /**
     * @covers Gloubster\Message\Job\ImageJob::isOk
     */
    public function testIsImageJobOk()
    {
        $object = $this->getJob();
        $this->assertFalse($object->isOk());
        $object->setParameters(array('format'=>'jpg'));
        $this->assertTrue($object->isOk());
    }

    /**
     * @covers Gloubster\Message\Job\ImageJob::isOk
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testIsImageJobNotOkWithException()
    {
        $this->getJob()->isOk(true);
    }

    /**
     * @covers Gloubster\Message\Job\ImageJob::isOk
     */
    public function testIsImageJobOkWithException()
    {
        $object = $this->getJob();
        $object->setParameters(array('format'=>'jpg'));
        $this->assertTrue($object->isOk(true));
    }

    /**
     * @covers Gloubster\Message\Job\ImageJob::getSource
     */
    public function testGetSource()
    {
        $this->assertTrue(file_exists($this->getJob()->getSource()));
    }
}
