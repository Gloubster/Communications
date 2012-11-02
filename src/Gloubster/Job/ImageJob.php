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

use Gloubster\Delivery\DeliveryInterface;

class ImageJob extends AbstractJob
{
     const RESOLUTION_PER_INCHES = 'inch';
     const RESOLUTION_PER_CENTIMETERS = 'cm';

     const RESIZE_INBOUND = 'in';
     const RESIZE_OUTBOUND = 'out';
     const RESIZE_INBOUND_FIXEDRATIO = 'in_fixed';

    private $source;
    private $parameters;
    private $delivery;

    public function __construct($source, DeliveryInterface $delivery, array $parameters = array())
    {
        parent::__construct();

        $this->source = $source;
        $this->delivery = $delivery;
        $this->parameters = $parameters;
    }

    public function isOk($throwException = false)
    {
        $missing = array();

        foreach (array('format') as $parameter) {
            if (!isset($this->parameters[$parameter])) {
                $missing[] = $parameter;
            }
        }

        if ($throwException) {
            throw new \RuntimeException('Missing parameters : ', implode(', ', $missing));
        }

        return count($missing) === 0;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getDelivery()
    {
        return $this->delivery;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getRoutingKey()
    {
        return 'phrasea.subdefs.image';
    }

    public function getExchangeName()
    {
        return 'phrasea.subdefs.dispatcher';
    }
}
