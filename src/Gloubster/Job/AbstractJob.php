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

abstract class AbstractJob implements JobInterface
{
    private $beginning;
    private $end;
    private $processDuration;
    private $deliveryDuration;

    public function __construct()
    {
        $this->beginning = new \DateTime();
    }

    public function getBeginning()
    {
        return $this->beginning;
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
            throw new \RuntimeException('Unable to unserialize data');
        }

        foreach ($data as $key => $serializedValue) {
            $this->{$key} = unserialize($serializedValue);
        }

        return $this;
    }
}
