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

abstract class AbstractJob implements JobInterface
{
    private $beginning;
    private $end;
    private $error;
    private $processDuration;
    private $deliveryDuration;
    private $workerId;
    protected $parameters;
    protected $delivery;

    public function __construct()
    {
        $this->beginning = new \DateTime();
        $this->error = false;
    }

    public function isOk($throwException = false)
    {
        $missing = array();

        foreach ($this->getMandatoryParameters() as $parameter) {
            if (!isset($this->parameters[$parameter])) {
                $missing[] = $parameter;
            }
        }

        if ($throwException) {
            throw new RuntimeException(sprintf('Missing parameters : %s', implode(', ', $missing)));
        }

        return count($missing) === 0;
    }

    public function getDelivery()
    {
        return $this->delivery;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function setError($boolean)
    {
        $this->error = (Boolean) $boolean;

        return $this;
    }

    public function isOnError()
    {
        return $this->error;
    }

    public function getBeginning()
    {
        return $this->beginning;
    }

    public function setEnd(\DateTime $date)
    {
        $this->end = $date;

        return $this;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setProcessDuration($duration)
    {
        $this->processDuration = $duration;

        return $this;
    }

    public function getProcessDuration()
    {
        return $this->processDuration;
    }

    public function setDeliveryDuration($duration)
    {
        $this->deliveryDuration = $duration;

        return $this;
    }

    public function getDeliveryDuration()
    {
        return $this->deliveryDuration;
    }

    public function setWorkerId($id)
    {
        $this->workerId = $id;

        return $this;
    }

    public function getWorkerId()
    {
        return $this->workerId;
    }

    public function requireReceipt()
    {
        return false;
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
