<?php

namespace Gloubster\Job;

use Gloubster\Delivery\FileSystem;

/**
 * @covers Gloubster\Job\AbstractJob
 * @covers Gloubster\Job\VideoJob
 */
class VideoJobTest extends AbstractJobTest
{

    public function getJob()
    {
        return new VideoJob(__DIR__ . '/../../testfiles/photo02.JPG', new FileSystem(__DIR__ . '/../../target.jpg'));
    }

    /**
     * @covers Gloubster\Job\VideoJob::isOk
     */
    public function testIsVideoJobOk()
    {
        $this->assertTrue($this->object->isOk());
    }

    /**
     * @covers Gloubster\Job\VideoJob::getSource
     */
    public function testGetSource()
    {
        $this->assertTrue(file_exists($this->object->getSource()));
    }
}
