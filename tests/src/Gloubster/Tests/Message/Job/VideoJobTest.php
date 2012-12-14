<?php

namespace Gloubster\Tests\Message\Job;

use Gloubster\Message\Job\VideoJob;
use Gloubster\Delivery\Filesystem;

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
            ->setDelivery(Filesystem::create(__DIR__ . '/../../../../target.jpg'));
    }

    public function getWrongJobs()
    {
        $delivery = $this->getMockBuilder('Gloubster\\Delivery\\DeliveryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $job1 = new VideoJob();

        $job2 = new VideoJob();
        $job2->setSource('/pathToSource');

        $job3 = new VideoJob();
        $job3->setDelivery($delivery);

        return array(
            array($job1),
            array($job2),
            array($job3),
        );
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

    public function testCreateJob()
    {
        $delivery = $this->getMockBuilder('Gloubster\\Delivery\\DeliveryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $job = VideoJob::create('/path/to/source', $delivery);
        $this->assertTrue($job->isOk());
    }
}
