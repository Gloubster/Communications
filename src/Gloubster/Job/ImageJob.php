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

use Gloubster\Exchange;
use Gloubster\RoutingKey;
use Gloubster\Delivery\DeliveryInterface;

class ImageJob extends AbstractJob
{
    const RESOLUTION_PER_INCHES = 'inch';
    const RESOLUTION_PER_CENTIMETERS = 'cm';

    const RESIZE_INBOUND = 'in';
    const RESIZE_OUTBOUND = 'out';
    const RESIZE_INBOUND_FIXEDRATIO = 'in_fixed';

    protected $source;

    public function __construct($source, DeliveryInterface $delivery, array $parameters = array())
    {
        parent::__construct();

        $this->source = $source;
        $this->delivery = $delivery;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getMandatoryParameters()
    {
        return array('format');
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutingKey()
    {
        return RoutingKey::IMAGE_PROCESSING;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeName()
    {
        return Exchange::GLOUBSTER_DISPATCHER;
    }
}
