<?php

namespace Gloubster\Job;

class ResultTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Gloubster\Job\Result::__construct
     * @covers Gloubster\Job\Result::getJobHandle
     * @covers Gloubster\Job\Result::getUuid
     * @covers Gloubster\Job\Result::getWorkload
     * @covers Gloubster\Job\Result::getBinaryData
     * @covers Gloubster\Job\Result::getDuration
     * @covers Gloubster\Job\Result::getInfos
     */
    public function testGetters()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');

        $result = new Result($jobHandle, $uuid, $workload, $binaryData, $duration, $infos);

        $this->assertEquals($jobHandle, $result->getJobHandle());
        $this->assertEquals($uuid, $result->getUuid());
        $this->assertEquals($workload, $result->getWorkload());
        $this->assertEquals($binaryData, $result->getBinaryData());
        $this->assertEquals($duration, $result->getDuration());
        $this->assertEquals($infos, $result->getInfos());
    }

    /**
     * @covers Gloubster\Job\Result::serialize
     * @covers Gloubster\Job\Result::unserialize
     */
    public function testSerialize()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');

        $result = new Result($jobHandle, $uuid, $workload, $binaryData, $duration, $infos);
        $result2 = unserialize(serialize($result));

        $this->assertEquals($result, $result2);
    }

    /**
     * @covers Gloubster\Job\Result::unserialize
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testSerializingCorruption()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');

        $result = $this->getMock('Gloubster\Job\Result', array('serialize'), array($jobHandle, $uuid, $workload, $binaryData, $duration, $infos));

        $result->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue('prout !')
        );

        unserialize(serialize($result));
    }
}

