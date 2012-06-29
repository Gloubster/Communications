<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Job;

use Gloubster\Exception\RuntimeException;

class Result implements \Serializable
{
    protected $jobHandle;
    protected $uuid;
    protected $workload;
    protected $binaryData;
    protected $duration;
    protected $infos;

    public function __construct($jobHandle, $uuid, $workload, $binaryData, $duration, array $infos = array())
    {
        $this->jobHandle = $jobHandle;
        $this->uuid = $uuid;
        $this->workload = $workload;
        $this->binaryData = $binaryData;
        $this->duration = $duration;
        $this->infos = $infos;
    }

    public function getJobHandle()
    {
        return $this->jobHandle;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getWorkload()
    {
        return $this->workload;
    }

    public function getBinaryData()
    {
        return $this->binaryData;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getInfos()
    {
        return $this->infos;
    }

    public function serialize()
    {
        $datas = array();

        foreach ($this as $prop => $data) {
            $datas[$prop] = $data;
        }

        return json_encode($datas);
    }

    public function unserialize($datas)
    {
        if(null === $datas = @json_decode($datas, true)) {
            throw new RuntimeException('Corrupted datas');
        }

        foreach ($datas as $prop => $data) {
            $this->$prop = $data;
        }
    }
}
