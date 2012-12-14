<?php

namespace Gloubster\Tests\Message\Presence;

use Gloubster\Message\Presence\WorkerPresence;

class WorkerPresenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WorkerPresence
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new WorkerPresence;
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getName
     */
    public function testGetName()
    {
        $this->assertInternalType('string', $this->object->getName());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::setId
     * @covers Gloubster\Message\Presence\WorkerPresence::getId
     */
    public function testGetSetId()
    {
        $id = mt_rand();
        $this->object->setId($id);
        $this->assertEquals($id, $this->object->getId());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getMemory
     * @covers Gloubster\Message\Presence\WorkerPresence::setMemory
     */
    public function testGetSetMemory()
    {
        $memory = 123456789.987654321;
        $this->object->setMemory($memory);
        $this->assertEquals($memory, $this->object->getMemory());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getStartedTime
     * @covers Gloubster\Message\Presence\WorkerPresence::setStartedTime
     */
    public function testGetSetStartedTime()
    {
        $time = 223456789.987654322;
        $this->object->setStartedTime($time);
        $this->assertEquals((float)(string)$time, $this->object->getStartedTime());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getStartedTime
     * @covers Gloubster\Message\Presence\WorkerPresence::setStartedTime
     */
    public function testGetSetNullStartedTime()
    {
        $time = null;
        $this->object->setStartedTime($time);
        $this->assertEquals($time, $this->object->getStartedTime());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getLastJobTime
     * @covers Gloubster\Message\Presence\WorkerPresence::setLastJobTime
     */
    public function testGetSetLastJobTime()
    {
        $time = 333456789.987654333;
        $this->object->setLastJobTime($time);
        $this->assertEquals((float)(string)$time, $this->object->getLastJobTime());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getLastJobTime
     * @covers Gloubster\Message\Presence\WorkerPresence::setLastJobTime
     */
    public function testGetSetNullLastJobTime()
    {
        $time = null;
        $this->object->setLastJobTime($time);
        $this->assertEquals($time, $this->object->getLastJobTime());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getTotalJobs
     * @covers Gloubster\Message\Presence\WorkerPresence::setTotalJobs
     * @todo   Implement testGetTotalJobs().
     */
    public function testGetSetTotalJobs()
    {
        $total = mt_rand();
        $this->object->setTotalJobs($total);
        $this->assertEquals($total, $this->object->getTotalJobs());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getSuccessJobs
     * @covers Gloubster\Message\Presence\WorkerPresence::setSuccessJobs
     */
    public function testGetSetSuccessJobs()
    {
        $success = mt_rand();
        $this->object->setSuccessJobs($success);
        $this->assertEquals($success, $this->object->getSuccessJobs());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getReportTime
     * @covers Gloubster\Message\Presence\WorkerPresence::setReportTime
     */
    public function testGetSetReportTime()
    {
        $time = 444456789.987654444;
        $this->object->setReportTime($time);
        $this->assertEquals((float)(string)$time, $this->object->getReportTime());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getReportTime
     * @covers Gloubster\Message\Presence\WorkerPresence::setReportTime
     */
    public function testGetSetNullReportTime()
    {
        $time = null;
        $this->object->setReportTime($time);
        $this->assertEquals($time, $this->object->getReportTime());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::isIdle
     * @covers Gloubster\Message\Presence\WorkerPresence::setIdle
     */
    public function testIsSetIdle()
    {
        $idle = true;
        $this->object->setIdle($idle);
        $this->assertEquals($idle, $this->object->isIdle());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getFailureJobs
     * @covers Gloubster\Message\Presence\WorkerPresence::setFailureJobs
     */
    public function testGetSetFailureJobs()
    {
        $total = mt_rand();
        $this->object->setFailureJobs($total);
        $this->assertEquals($total, $this->object->getFailureJobs());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getWorkerType
     * @covers Gloubster\Message\Presence\WorkerPresence::setWorkerType
     */
    public function testGetWorkerType()
    {
        $type = 'worker-type';
        $this->object->setWorkerType($type);
        $this->assertEquals($type, $this->object->getWorkerType());
    }

    /**
     * @covers Gloubster\Message\Presence\WorkerPresence::getArrayData
     * @covers Gloubster\Message\AbstractMessage::toJson
     * @covers Gloubster\Message\AbstractMessage::fromJson
     */
    public function testFromToJson()
    {
        $type = 'worker-type';
        $this->object->setWorkerType($type);
        $total = mt_rand();
        $this->object->setFailureJobs($total);
        $id = mt_rand();
        $this->object->setId($id);
        $memory = 123456789;
        $this->object->setMemory($memory);
        $time = 223456789.987654322;
        $this->object->setStartedTime($time);
        $time = 333456789.987654333;
        $this->object->setLastJobTime($time);
        $total = mt_rand();
        $this->object->setTotalJobs($total);
        $success = mt_rand();
        $this->object->setSuccessJobs($success);
        $time = 444456789.987654444;
        $this->object->setReportTime($time);
        $idle = true;
        $this->object->setIdle($idle);

        $this->assertEquals($this->object, WorkerPresence::fromJson($this->object->toJson()));
    }
}
