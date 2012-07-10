<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Communication;

use Gloubster\Exception\RuntimeException;

class Result implements \Serializable
{
    protected $jobHandle;
    protected $uuid;
    protected $workload;
    protected $binaryData;
    protected $workerName;
    protected $start;
    protected $stop;
    protected $infos;
    protected $errors;
    protected $timers;

    public function __construct($jobHandle, $uuid, $workload, $binaryData, $workerName, $start, $stop, array $infos = array(), array $errors = array())
    {
        $this->jobHandle = $jobHandle;
        $this->uuid = $uuid;
        $this->workload = $workload;
        $this->binaryData = $binaryData;
        $this->workerName = $workerName;
        $this->start = $start;
        $this->stop = $stop;
        $this->infos = $infos;
        $this->errors = $errors;
    }

    public function getJobHandle()
    {
        return $this->jobHandle;
    }

    public function getTimers()
    {
        return $this->timers;
    }

    public function setTimers(array $timers)
    {
        return $this->timers = $timers;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getWorkload()
    {
        return $this->workload;
    }

    public function getWorkerName()
    {
        return $this->workerName;
    }

    public function getBinaryData()
    {
        return $this->binaryData;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getStop()
    {
        return $this->stop;
    }

    public function getDuration()
    {
        return $this->stop - $this->start;
    }

    public function getInfos()
    {
        return $this->infos;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function serialize()
    {
        $datas = array();

        foreach ($this as $prop => $data) {

            if ($prop == 'binaryData') {
                $data = base64_encode($data);
            }

            if(in_array($prop, array('start', 'stop', 'timers'))){
                $data = serialize($data);
            }

            $datas[$prop] = $data;
        }

        return json_encode($datas);
    }

    public function unserialize($datas)
    {
        if (null === $datas = @json_decode($datas, true)) {
            throw new RuntimeException('Corrupted datas');
        }

        foreach ($datas as $prop => $data) {

            if ($prop == 'binaryData') {
                $data = base64_decode($data);
            }

            if(in_array($prop, array('start', 'stop', 'timers'))){
                $data = unserialize($data);
            }

            $this->$prop = $data;
        }
    }
}
