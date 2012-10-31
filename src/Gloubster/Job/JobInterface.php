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

interface JobInterface
{

    /**
     * The message as it will be send to AMQP server
     */
    public function getAMQPMessage();

    public function getRoutingKey();

    public function getExchangeName();
}

