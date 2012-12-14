<?php

namespace Gloubster\Tests\Message\Job;

use Gloubster\Message\Job\VideoJob;
use Gloubster\Delivery\FileSystem;

/**
 * @covers Gloubster\Message\Job\AbstractJob
 * @covers Gloubster\Message\Job\VideoJob
 */
class VideoJobTest extends AbstractJobTest
{

    public function getJob()
    {
        $job = new VideoJob();

        return $job->setSource(__DIR__ . '/../../../../testfiles/photo02.JPG')
            ->setDelivery(new FileSystem(__DIR__ . '/../../../../target.jpg'));
    }

    /**
     * @covers Gloubster\Message\Job\VideoJob::isOk
     */
    public function testIsVideoJobOk()
    {
        $this->assertTrue($this->object->isOk());
    }

    /**
     * @covers Gloubster\Message\Job\VideoJob::getSource
     */
    public function testGetSource()
    {
        $this->assertTrue(file_exists($this->object->getSource()));
    }
}
