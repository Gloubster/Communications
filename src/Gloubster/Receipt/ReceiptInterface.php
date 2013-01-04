<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Receipt;

use Gloubster\Message\Job\JobInterface;

interface ReceiptInterface
{
    /**
     * Returns
     */
    public function getName();

    /**
     * Acknowledge the reception
     *
     * @param JobInterface $job
     */
    public function acknowledge(JobInterface $job);

    /**
     * Returns an Array representation of the Receipt
     *
     * @return Array
     */
    public function toArray();

    /**
     * Creates a new receipt instance based on serialized data
     *
     * @param array $data Serialized data
     *
     * @return ReceiptInterface
     */
    public static function fromArray(array $data);
}

