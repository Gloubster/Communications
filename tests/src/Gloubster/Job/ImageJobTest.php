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
        $this->assertFalse($this->object->isOk());
        $this->object->setParameters(array('format'=>'jpg'));
        $this->assertTrue($this->object->isOk());
    }

    /**
     * @covers Gloubster\Job\ImageJob::isOk
     * @expectedException RuntimeException
     */
    public function testIsImageJobOkWithException()
    {
        $this->object->isOk(true);
    }

    /**
     * @covers Gloubster\Job\ImageJob::getSource
     */
    public function testGetSource()
    {
        $this->assertTrue(file_exists($this->object->getSource()));
    }
}
