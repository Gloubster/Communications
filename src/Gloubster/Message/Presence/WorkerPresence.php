<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Message\Presence;

use Gloubster\Message\AbstractMessage;
use Gloubster\Message\MessageInterface;

class WorkerPresence extends AbstractMessage implements MessageInterface, PresenceInterface
{
    private $failureJobs;
    private $id;
    private $idle;
    private $lastJobTime;
    private $memory;
    private $reportTime;
    private $startedTime;
    private $successJobs;
    private $totalJobs;
    private $workerType;

    public function getName()
    {
        return 'presence';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getMemory()
    {
        return $this->memory;
    }

    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    public function getStartedTime()
    {
        return (double) $this->startedTime;
    }

    public function setStartedTime($startedTime)
    {
        $this->startedTime = null !== $startedTime ? (string) $startedTime : null;

        return $this;
    }

    public function getLastJobTime()
    {
        return (float) $this->lastJobTime;
    }

    public function setLastJobTime($lastJobTime)
    {
        $this->lastJobTime = null !== $lastJobTime ? (string) $lastJobTime : null;

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
        return (float) $this->reportTime;
    }

    public function setReportTime($reportTime)
    {
        $this->reportTime = null !== $reportTime ? (string) $reportTime : null;

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

    public function getWorkerType()
    {
        return $this->workerType;
    }

    public function setWorkerType($workerType)
    {
        $this->workerType = $workerType;

        return $this;
    }

    protected function getArrayData()
    {
        $data = array();

        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }
}
