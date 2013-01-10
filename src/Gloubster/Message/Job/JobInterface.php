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

use Gloubster\Message\MessageInterface;
use Gloubster\Delivery\DeliveryInterface;
use Gloubster\Exception\InvalidArgumentException;
use Gloubster\Exception\RuntimeException;
use Gloubster\Receipt\ReceiptInterface;

interface JobInterface extends MessageInterface
{

    /**
     * Returns true if all mandatory parameters are present.
     * An exception can be thrown if requested.
     *
     * @param Boolean $throwException
     *
     * @return Boolean
     *
     * @throws RuntimeException in case exception have been requested
     */
    public function isOk($throwException = false);

    /**
     * Sets error flag.
     *
     * @param Boolean $boolean
     *
     * @return JobInterface
     */
    public function setError($boolean);

    /**
     * Sets processing error message.
     *
     * @param string $message
     *
     * @return JobInterface
     */
    public function setErrorMessage($message);

    /**
     * Returns the processing error message.
     *
     * @return string
     */
    public function getErrorMessage();

    /**
     * Adds a key/value parameter.
     *
     * If a parameter with the same key already exists, it will be overriden.
     *
     * @param string $name
     * @param string $value
     *
     * @return JobInterface
     */
    public function addParameter($name, $value);

    /**
     * Sets an array of key/value parameters.
     *
     * @param array $parameters
     *
     * @return JobInterface
     */
    public function setParameters(array $parameters);

    /**
     * Returns an array of key/value parameters attached to this job.
     *
     * @return array
     */
    public function getParameters();

    /**
     * Returns a parameter depending on its key.
     *
     * @param string $name
     *
     * @return string the value
     *
     * @throws InvalidArgumentException In case the parameter does not exist.
     */
    public function getParameter($name);

    /**
     * Checks the presence of a parameter.
     *
     * @param string $name
     *
     * @return Boolean return true if the parameter is set
     */
    public function hasParameter($name);

    /**
     * Removes the parameter.
     *
     * @param string $name
     *
     * @return JobInterface
     *
     * @throws InvalidArgumentException In case the parameter does not exist
     */
    public function removeParameter($name);

    /**
     * Returns an array of key/value parameters required to execute the job.
     *
     * @return array
     */
    public function getMandatoryParameters();

    /**
     * Returns true if the job has been processed and the error flag has been set.
     *
     * @return Boolean
     */
    public function isOnError();

    /**
     * Returns true if one or more receipts are required.
     *
     * @return Boolean
     */
    public function requireReceipt();

    /**
     * Returns an array of ReceiptInterface as post-process receipt.
     *
     * @return array
     */
    public function getReceipts();

    /**
     * Sets an array of ReceiptInterface as receipts.
     *
     * @param array $receipts
     *
     * @return JobInterface
     */
    public function setReceipts(array $receipts);

    /**
     * Pushes a Receipt to the array of receipts.
     *
     * @param ReceiptInterface $receipt
     *
     * @return JobInterface
     */
    public function addReceipt(ReceiptInterface $receipt);

    /**
     * Sets the worker Id that has processed the Job.
     *
     * @param string $id
     *
     * @return JobInterface
     */
    public function setWorkerId($id);

    /**
     * Returns the worked Id taht has processed the job.
     *
     * @return null|string Returns null in case the job has not yet been processed.
     */
    public function getWorkerId();

    /**
     * Returns the delivery method that is used with this file.
     *
     * @return DeliveryInterface
     */
    public function getDelivery();

    /**
     * Sets the delivery method for this job.
     *
     * @param DeliveryInterface $delivery The delivery
     *
     * @return JobInterface
     */
    public function setDelivery(DeliveryInterface $delivery);

    /**
     * Returns the timestamp when the job has been created with microsecond precision.
     *
     * @return float
     */
    public function getBeginning();

    /**
     * Sets the beginning timestamp (with microseconds) of a Job
     *
     * @param float $beginning
     *
     * @return JobInterface
     */
    public function setBeginning($beginning);

    /**
     * Returns the timestamp when the job has been finished with microsecond precision.
     * The job is considered finish when processed and delivered.
     *
     * @return float
     */
    public function getEnd();

    /**
     * Sets the job end time. Job ends when it has been processed and delivered.
     *
     * @param float $microtime
     *
     * @return JobInterface
     */
    public function setEnd($microtime);

    /**
     * Sets the process duration in seconds with microsecond precision.
     *
     * @param float $duration
     *
     * @return JobInterface
     */
    public function setProcessDuration($duration);

    /**
     * Returns the process duration in seconds with microsecond precision.
     *
     * @return float
     */
    public function getProcessDuration();

    /**
     * Sets the delivery duration in seconds with microsecond precision.
     *
     * @param float $duration
     *
     * @return JobInterface
     */
    public function setDeliveryDuration($duration);

    /**
     * Returns the delivery duration in seconds with microsecond precision.
     *
     * @return float
     */
    public function getDeliveryDuration();

    /**
     * Returns the RabbitMQ routing key name that has been used.
     *
     * @return string
     */
    public function getRoutingKey();

    /**
     * Returns the RabbitMQ exchange name that has been used.
     *
     * @return string
     */
    public function getExchangeName();
}

