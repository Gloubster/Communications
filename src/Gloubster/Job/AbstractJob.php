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

use Gloubster\Exception\InvalidArgumentException;
use Gloubster\Exception\RuntimeException;
use Gloubster\Receipt\ReceiptInterface;

abstract class AbstractJob implements JobInterface
{
    private $beginning;
    private $end;
    private $error;
    private $errorMessage;
    private $processDuration;
    private $deliveryDuration;
    private $workerId;
    private $receipts;
    protected $parameters;
    protected $delivery;

    public function __construct()
    {
        $this->beginning = microtime(true);
        $this->error = false;
    }

    /**
     * {@inheritdoc}
     */
    public function isOk($throwException = false)
    {
        $missing = array();

        foreach ($this->getMandatoryParameters() as $parameter) {
            if (!isset($this->parameters[$parameter])) {
                $missing[] = $parameter;
            }
        }

        if (0 < count($missing) && $throwException) {
            throw new RuntimeException(sprintf('Missing parameters : %s', implode(', ', $missing)));
        }

        return count($missing) === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setError($boolean)
    {
        $this->error = (Boolean) $boolean;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isOnError()
    {
        return $this->error;
    }

    /**
     * {@inheritdoc}
     */
    public function getBeginning()
    {
        return $this->beginning;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnd($microtime)
    {
        $this->end = $microtime;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * {@inheritdoc}
     */
    public function setProcessDuration($duration)
    {
        $this->processDuration = $duration;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessDuration()
    {
        return $this->processDuration;
    }

    /**
     * {@inheritdoc}
     */
    public function setDeliveryDuration($duration)
    {
        $this->deliveryDuration = $duration;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveryDuration()
    {
        return $this->deliveryDuration;
    }

    /**
     * {@inheritdoc}
     */
    public function setWorkerId($id)
    {
        $this->workerId = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkerId()
    {
        return $this->workerId;
    }

    /**
     * {@inheritdoc}
     */
    public function requireReceipt()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $data = array();

        foreach ($this as $key => $parameter) {
            $data[$key] = serialize($parameter);
        }

        return serialize($data);
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function setReceipts(array $receipts)
    {
        array_map(function($receipt) {
            if (!$receipt instanceof ReceiptInterface) {
                throw new InvalidArgumentException('setReceipts only accept ReceiptInterface');
            }
        }, $receipts);

        $this->receipts = $receipts;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getReceipts()
    {
        return $this->receipts;
    }

    /**
     * {@inheritdoc}
     */
    public function pushReceipt(ReceiptInterface $receipt)
    {
        array_push($this->receipts, $receipt);

        return $this;
    }
}
