<?php

namespace Gloubster\Tests\Message\Presence;

use Gloubster\Message\Presence\Factory;
use Gloubster\Message\Presence\WorkerPresence;
use Gloubster\Message\Job\ImageJob;

class FactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Gloubster\Message\Presence\Factory::fromJson
     */
    public function testFromJson()
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

        $this->assertEquals($presence, Factory::fromJson($presence->toJson()));
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testFromJsonFailsWithWrongType()
    {
        $job = new ImageJob();

        Factory::fromJson($job->toJson());
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
