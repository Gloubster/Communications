<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Delivery;

use Gloubster\Job\Result;

/**
 * Interface for deliveries.
 *
 * Delivery happens when a worker has finished his job. It returns the result
 * through the job delivery module
 */
interface DeliveryInterface
{
    /**
     * Return the name of the delivery module. The name must be the classname
     * inside the Gloubster\Delivery namespace
     *
     * @return string
     */
    public function getName();

    /**
     * Return a signature for the current delivery.
     * The signature must be configuration dependant.
     *
     * This signature is used to confirm that a Worker uses the same
     * configuration for the delivery module as the Client
     *
     * @return string
     */
    public function getSignature();

    /**
     * The deliver method is used by the Worker to deliver the output data.
     *
     * @param string $uuid The unique Id related to the job
     * @param Result $result The result of the job
     *
     * @throws Gloubster\Exception\RuntimeException On failure
     */
    public function deliver($uuid, Result $result);

    /**
     * The client use the retrieve method to retrieve the result of the worker
     *
     * @param string $uuid
     *
     * @throws Gloubster\Delivery\Exception\ItemDoesNotExistsException On failure
     * @throws Gloubster\Exception\RuntimeException On failure
     *
     * @return Result
     */
    public function retrieve($uuid);

    /**
     * Build the delivery giving the configuration
     *
     * @param array $configuration
     *
     * @throws Gloubster\Exception\RuntimeException On failure
     */
    public static function build(array $configuration);
}