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

use Gloubster\RabbitMQ\Configuration as RabbitMQConfiguration;

class VideoJob extends ImageJob
{
    protected $source;

    public function getName()
    {
        return 'video';
    }

    public function getMandatoryParameters()
    {
        return array();
    }

    public function getRoutingKey()
    {
        return RabbitMQConfiguration::ROUTINGKEY_VIDEO_PROCESSING;
    }

    public function getExchangeName()
    {
        return RabbitMQConfiguration::EXCHANGE_DISPATCHER;
    }
}