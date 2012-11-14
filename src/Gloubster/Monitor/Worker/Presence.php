<?php

namespace Gloubster\Monitor\Worker;

use Gloubster\MessageInterface;

class Presence implements MessageInterface
{
    private $startedTime;
    private $id;
    private $idle;
    private $lastJobTime;
    private $totalJobs;
    private $successJobs;
    private $failureJobs;
    private $reportTime;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getStartedTime()
    {
        return $this->startedTime;
    }

    public function setStartedTime($startedTime)
    {
        $this->startedTime = $startedTime;

        return $this;
    }

    public function getLastJobTime()
    {
        return $this->lastJobTime;
    }

    public function setLastJobTime($lastJobTime)
    {
        $this->lastJobTime = $lastJobTime;

        return $this;
    }

    public function getTotalJobs()
    {
        return $this->totalJobs;
    }

    public function setTotalJobs($totalJobs)
    {
        $this->totalJobs = $totalJobs;

        return $this;
    }

    public function getSuccessJobs()
    {
        return $this->successJobs;
    }

    public function setSuccessJobs($successJobs)
    {
        $this->successJobs = $successJobs;

        return $this;
    }

    public function getReportTime()
    {
        return $this->reportTime;
    }

    public function setReportTime($reportTime)
    {
        $this->reportTime = $reportTime;

        return $this;
    }

    public function isIdle()
    {
        return $this->idle;
    }

    public function setIdle($boolean)
    {
        $this->idle = $boolean;

        return $this;
    }

    public function getFailureJobs()
    {
        return $this->failureJobs;
    }

    public function setFailureJobs($failureJobs)
    {
        $this->failureJobs = $failureJobs;

        return $this;
    }

    public function serialize()
    {
        $data = array();

        foreach ($this as $key => $parameter) {
            $data[$key] = serialize($parameter);
        }

        return serialize($data);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        if (!is_array($data)) {
            throw new RuntimeException('Unable to unserialize data');
        }

        foreach ($data as $key => $serializedValue) {
            $this->{$key} = unserialize($serializedValue);
        }

        return $this;
    }
}
