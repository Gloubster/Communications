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
use Gloubster\Exception\RuntimeException;

class ImageJob extends AbstractJob
{
    const RESOLUTION_PER_INCHES = 'inch';
    const RESOLUTION_PER_CENTIMETERS = 'cm';

    const RESIZE_INBOUND = 'in';
    const RESIZE_OUTBOUND = 'out';
    const RESIZE_INBOUND_FIXEDRATIO = 'in_fixed';

    protected $source;

    public function getName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function getMandatoryParameters()
    {
        return array('format');
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
    public function getRoutingKey()
    {
        return RabbitMQConfiguration::ROUTINGKEY_IMAGE_PROCESSING;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeName()
    {
        return RabbitMQConfiguration::EXCHANGE_DISPATCHER;
    }

    public function isOk($throwException = false)
    {
        if (null === $this->source) {
            if ($throwException) {
                throw new RuntimeException('No source set for this Job');
            }

            return false;
        }

        return parent::isOk($throwException);
    }
}
