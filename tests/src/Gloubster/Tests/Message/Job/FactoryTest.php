<?php

namespace Gloubster\Tests\Message\Job;

use Gloubster\Message\Job\Factory;
use Gloubster\Message\Job\ImageJob;
use Gloubster\Message\Job\VideoJob;
use Gloubster\Message\Presence\WorkerPresence;
use Gloubster\Delivery\Filesystem as FilesystemDelivery;

class FactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getData
     * @covers Gloubster\Message\Job\Factory::fromJson
     */
    public function testFromJson($expected, $json)
    {
        $this->assertEquals($expected, Factory::fromJson($json));
    }

    public function getData()
    {
        $delivery = new FilesystemDelivery();
        $delivery->setTarget('/path/to/Target');

        /**
         * @todo add receipts
         */
        $receipts = array();

        $image = new ImageJob();
        $image->setEnd(microtime(true))
            ->setDeliveryDuration(25.4)
            ->setDelivery($delivery)
            ->setError(true)
            ->setErrorMessage('An error ploped')
            ->setParameters(array('param1'=>'val1', 'param2'=>'val2'))
            ->setProcessDuration(23.42)
            ->setWorkerId('a worker-id')
            ->setSource('/path/to/source')
            ->setReceipts($receipts);

        $video = new VideoJob();
        $image->setEnd(microtime(true))
            ->setDeliveryDuration(25.4)
            ->setDelivery($delivery)
            ->setError(true)
            ->setErrorMessage('An error ploped')
            ->setParameters(array('param1'=>'val1', 'param2'=>'val2'))
            ->setProcessDuration(23.42)
            ->setWorkerId('a worker-id')
            ->setSource('/path/to/source')
            ->setReceipts($receipts);

        return array(
            array($image, $image->toJson()),
            array($video, $video->toJson()),
        );
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     * @covers Gloubster\Message\Job\Factory::fromJson
     */
    public function testFromJsonFailsWithWrongType()
    {
        $presence = new WorkerPresence();
        $presence->setWorkerType('worker-type')
            ->setFailureJobs(mt_rand())
            ->setId(mt_rand())
            ->setMemory(123456789)
            ->setStartedTime(223456789.987654322)
            ->setLastJobTime(333456789.987654333)
            ->setTotalJobs(mt_rand())
            ->setSuccessJobs(mt_rand())
            ->setReportTime(444456789.987654444)
            ->setIdle(true);

        Factory::fromJson($presence->toJson());
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testFromJsonFailsWithWrongData()
    {
        Factory::fromJson('data');
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testFromJsonFailsWithMissingType()
    {
        Factory::fromJson(json_encode(array('name' => 'hello')));
    }
}
