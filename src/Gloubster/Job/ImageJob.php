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

class ImageJob implements JobInterface
{
    private $source;
    private $parameters;
    private $delivery;

    public function __construct($source, DeliveryInterface $delivery, array $parameters = array())
    {
        $this->source = $source;
        $this->delivery = $delivery;
        $this->parameters = $parameters;
    }

    public function getAMQPMessage()
    {
        return serialize(array(
            'source'      => $this->source,
            'delivery'    => $this->delivery,
            'parameters'  => $this->parameters,
        ));
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
