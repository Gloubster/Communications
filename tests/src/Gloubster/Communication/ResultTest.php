<?php

namespace Gloubster\Communication;

class ResultTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Gloubster\Communication\Result::__construct
     * @covers Gloubster\Communication\Result::getJobHandle
     * @covers Gloubster\Communication\Result::getUuid
     * @covers Gloubster\Communication\Result::getWorkload
     * @covers Gloubster\Communication\Result::getBinaryData
     * @covers Gloubster\Communication\Result::getDuration
     * @covers Gloubster\Communication\Result::getInfos
     * @covers Gloubster\Communication\Result::getErrors
     */
    public function testGetters()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');
        $errors = array('There is an error', 'Something wrong happened');

        $result = new Result($jobHandle, $uuid, $workload, $binaryData, $duration, $infos, $errors);

        $this->assertEquals($jobHandle, $result->getJobHandle());
        $this->assertEquals($uuid, $result->getUuid());
        $this->assertEquals($workload, $result->getWorkload());
        $this->assertEquals($binaryData, $result->getBinaryData());
        $this->assertEquals($duration, $result->getDuration());
        $this->assertEquals($infos, $result->getInfos());
        $this->assertEquals($errors, $result->getErrors());
    }

    /**
     * @covers Gloubster\Communication\Result::serialize
     * @covers Gloubster\Communication\Result::unserialize
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
     * @covers Gloubster\Communication\Result::unserialize
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

        $result = $this->getMock('Gloubster\Communication\Result', array('serialize'), array($jobHandle, $uuid, $workload, $binaryData, $duration, $infos));

        $result->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue('prout !')
        );

        unserialize(serialize($result));
    }
}

