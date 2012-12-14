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

use Gloubster\Exchange;
use Gloubster\RoutingKey;
use Gloubster\Delivery\DeliveryInterface;

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
        return RoutingKey::VIDEO_PROCESSING;
    }

    public function getExchangeName()
    {
        return Exchange::GLOUBSTER_DISPATCHER;
    }
}
