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

class VideoJob implements JobInterface, \Serializable
{
    public function getBody()
    {
        return json_encode(array(
            'path'=>'file://path/to/image',
            'resolution'=>72
        ));
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