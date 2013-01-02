<?php

namespace Gloubster\Tests\Message\Acknowledgement;

use Gloubster\Message\Acknowledgement\Factory;
use Gloubster\Message\Acknowledgement\JobAcknowledgement;
use Gloubster\Message\Acknowledgement\JobNotAcknowledgement;
use Gloubster\Message\Presence\WorkerPresence;

class FactoryTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getData
     * @covers Gloubster\Message\Acknowledgement\Factory::fromJson
     */
    public function testFromJson($expected, $json)
    {
        $this->assertEquals($expected, Factory::fromJson($json));
    }

    public function getData()
    {
        $ack = new JobAcknowledgement();

        $nack = new JobNotAcknowledgement();
        $nack->setReason('Man machine');

        return array(
            array($ack, $ack->toJson()),
            array($nack, $nack->toJson()),
        );
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     * @covers Gloubster\Message\Acknowledgement\Factory::fromJson
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
