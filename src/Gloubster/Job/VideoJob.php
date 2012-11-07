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

class VideoJob extends AbstractJob
{
    protected $source;

    public function __construct($source, DeliveryInterface $delivery, array $parameters = array())
    {
        parent::__construct();

        $this->source = $source;
        $this->delivery = $delivery;
        $this->parameters = $parameters;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getMandatoryParameters()
    {
        return array();
    }

    public function getRoutingKey()
    {
        return 'phrasea.subdefs.video';
    }

    public function getExchangeName()
    {
        return 'phrasea.subdefs.dispatcher';
    }

}
