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
use Gloubster\Exception\RuntimeException;
use Gloubster\Receipt\ReceiptInterface;

interface JobInterface extends MessageInterface
{

    /**
     * Returns true if all mandatory parameters are present.
     * An exception can be thrown if requested.
     *
     * @param Boolean $throwException
     * @return Boolean
     * @throws RuntimeException in case exception have been requested
     */
    public function isOk($throwException = false);

    /**
     * Set error flag.
     *
     * @param Boolean $boolean
     * @return JobInterface
     */
    public function setError($boolean);

    /**
     * Set processing error message.
     *
     * @param string $message
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
     * Set an array of key/value parameters.
     *
     * @param array $parameters
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
     * Return an array of ReceiptInterface as post-process receipt.
     *
     * @return array
     */
    public function getReceipts();

    /**
     * Set an array of ReceiptInterface as receipts.
     *
     * @param array $receipts
     * @return JobInterface
     */
    public function setReceipts(array $receipts);

    /**
     * Push a Receipt to the array of receipts.
     *
     * @param ReceiptInterface $receipt
     * @return JobInterface
     */
    public function pushReceipt(ReceiptInterface $receipt);

    /**
     * Set the worker Id that has processed the Job.
     *
     * @param string $id
     * @return JobInterface
     */
    public function setWorkerId($id);

    /**
     * Return the worked Id taht has processed the job.
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
     * Returns the timestamp when the job has been created with microsecond precision.
     *
     * @return float
     */
    public function getBeginning();

    public function setBeginning($beginning);

    /**
     * Returns the timestamp when the job has been finished with microsecond precision.
     * The job is considered finish when processed and delivered.
     *
     * @return float
     */
    public function getEnd();

    /**
     * Set the job end time. Job ends when it has been processed and delivered.
     *
     * @param float $microtime
     * @return JobInterface
     */
    public function setEnd($microtime);

    /**
     * Set the process duration in seconds with microsecond precision.
     *
     * @param float $duration
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
     * Set the delivery duration in seconds with microsecond precision.
     *
     * @param float $duration
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

