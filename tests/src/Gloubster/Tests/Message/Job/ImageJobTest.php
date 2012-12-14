<?php

namespace Gloubster\Tests\Message\Job;

use Gloubster\Message\Job\ImageJob;
use Gloubster\Delivery\Filesystem;

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
            ->setDelivery(Filesystem::create(__DIR__ . '/../../../../target.jpg'))
            ->setParameters(array('format' => 'png'));
    }

    public function getWrongJobs()
    {
        $delivery = $this->getMockBuilder('Gloubster\\Delivery\\DeliveryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $job1 = new ImageJob();

        $job2 = new ImageJob();
        $job2->setSource('/pathToSource');

        $job3 = new ImageJob();
        $job3->setDelivery($delivery);

        $job4 = new ImageJob();
        $job4->setDelivery($delivery);
        $job4->setSource('/pathToSource');

        $job5 = new ImageJob();
        $job5->setDelivery($delivery);
        $job5->setParameters(array('format' => 'bingo'));

        $job6 = new ImageJob();
        $job6->setSource('/pathToSource');
        $job6->setParameters(array('format' => 'bingo'));

        return array(
            array($job1),
            array($job2),
            array($job3),
            array($job4),
            array($job5),
            array($job6),
        );
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
