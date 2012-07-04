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
        $start = microtime(true);
        $stop = microtime(true) + 0.45;
        $workerName = 'bidouille-26';
        $infos = array('this was pretty good');

        $result = $this->getMock('\Gloubster\Communication\Result', array('serialize'), array($jobHandle, $uuid, $workload, $binaryData, $workerName, $start, $stop, $infos));

        return $result;
    }

    protected function getResultObject()
    {
        $jobHandle = 'job-handle';
        $uuid = 'unique id';
        $workload = json_encode('datas');
        $binaryData = file_get_contents(__FILE__);
        $workerName = 'bidibule-24';
        $start = microtime(true);
        $stop = microtime(true) + 0.23;
        $infos = array('this was pretty good');

        return new Result($jobHandle, $uuid, $workload, $binaryData, $workerName, $start, $stop, $infos);
    }
}