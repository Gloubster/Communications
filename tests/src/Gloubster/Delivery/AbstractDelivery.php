<?php

namespace Gloubster\Delivery;

use Gloubster\Communication\Result;

abstract class AbstractDelivery extends \PHPUnit_Framework_TestCase
{

    protected function getResultMock()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');

        $result = $this->getMock('\Gloubster\Communication\Result', array('serialize'), array($jobHandle, $uuid, $workload, $binaryData, $duration, $infos));

        return $result;
    }

    protected function getResultObject()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $duration = 0.023;
        $infos = array('this was pretty good');

        return new Result($jobHandle, $uuid, $workload, $binaryData, $duration, $infos);
    }
}