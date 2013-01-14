<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Message\Job;

use Gloubster\Message\AbstractMessage;
use Gloubster\Delivery\DeliveryInterface;
use Gloubster\Exception\InvalidArgumentException;
use Gloubster\Exception\RuntimeException;
use Gloubster\Receipt\ReceiptInterface;

abstract class AbstractJob extends AbstractMessage implements JobInterface
{
    private $beginning;
    private $end;
    private $error;
    private $errorMessage;
    private $processDuration;
    private $deliveryDuration;
    private $workerId;
    private $result;
    private $receipts = array();
    private $source;
    protected $parameters = array();
    protected $delivery;

    public function __construct()
    {
        $this->beginning = (string) microtime(true);
        $this->error = false;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isOk($throwException = false)
    {
        if (null === $this->source) {
            if ($throwException) {
                throw new RuntimeException('No source set for this Job');
            }

            return false;
        }

        if (null === $this->delivery) {
            if ($throwException) {
                throw new RuntimeException('No delivery set for this Job');
            }

            return false;
        }

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
    public function setDelivery(DeliveryInterface $delivery = null)
    {
        $this->delivery = $delivery;

        return $this;
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
    public function getParameter($name)
    {
        if (!isset($this->parameters[$name])) {
            throw new InvalidArgumentException(sprintf('Parameter %s does not exist', $name));
        }

        return $this->parameters[$name];
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
    public function addParameter($name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function removeParameter($name)
    {
        if (!isset($this->parameters[$name])) {
            throw new InvalidArgumentException(sprintf('Parameter %s does not exist', $name));
        }

        unset($this->parameters[$name]);

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
        return null === $this->beginning ? $this->beginning : (float) $this->beginning;
    }

    /**
     * {@inheritdoc}
     */
    public function setBeginning($beginning)
    {
        $this->beginning = null !== $beginning ? (string) $beginning : null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnd($microtime)
    {
        $this->end = null !== $microtime ? (string) $microtime : null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd()
    {
        return null === $this->end ? $this->end : (float) $this->end;
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
        return 0 < count($this->receipts);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult(array $result = null)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setReceipts(array $receipts)
    {
        array_map(function ($receipt) {
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
    public function addReceipt(ReceiptInterface $receipt)
    {
        array_push($this->receipts, $receipt);

        return $this;
    }

    public static function create($source, DeliveryInterface $delivery, array $parameters = array())
    {
        $job = new static();

        return $job->setSource($source)
                ->setDelivery($delivery)
                ->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function getArrayData()
    {
        $data = array();

        foreach ($this as $key => $parameter) {
            if ($key === 'delivery' && null !== $parameter) {
                $data[$key] = $parameter->toArray();
            } elseif ($key === 'receipts' && null !== $parameter) {
                $data[$key] = array();
                foreach ($parameter as $receipt) {
                    $data[$key][] = $receipt->toArray();
                }
            } else {
                $data[$key] = $parameter;
            }
        }

        return $data;
    }
}
