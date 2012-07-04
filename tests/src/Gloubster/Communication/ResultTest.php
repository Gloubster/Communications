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
        $start = microtime(true);
        $stop = microtime(true) + 0.45;
        $workerName = 'bidouille-26';
        $infos = array('this was pretty good');
        $errors = array('There is an error', 'Something wrong happened');

        $result = new Result($jobHandle, $uuid, $workload, $binaryData, $workerName, $start, $stop, $infos, $errors);

        $this->assertEquals($jobHandle, $result->getJobHandle());
        $this->assertEquals($uuid, $result->getUuid());
        $this->assertEquals($workload, $result->getWorkload());
        $this->assertEquals($binaryData, $result->getBinaryData());
        $this->assertEquals($stop - $start, $result->getDuration());
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
        $binaryData = 'file_get_contents(__FILE__)';
        $start = microtime(true);
        $stop = microtime(true) + 0.45;
        $workerName = 'bidouille-26';
        $infos = array('this was pretty good');
        $errors = array();

        $result = new Result($jobHandle, $uuid, $workload, $binaryData, $workerName, $start, $stop, $infos, $errors);
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
        $start = microtime(true);
        $stop = microtime(true) + 0.45;
        $workerName = 'bidouille-26';
        $infos = array('this was pretty good');
        $errors = array();

        $result = $this->getMock('Gloubster\Communication\Result', array('serialize'), array($jobHandle, $uuid, $workload, $binaryData, $workerName, $start, $stop, $infos, $errors));

        $result->expects($this->once())
            ->method('serialize')
            ->will(
                $this->returnValue('prout !')
        );

        unserialize(serialize($result));
    }
}

