<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\RabbitMQ;

class Configuration
{
    const EXCHANGE_DISPATCHER = 'phrasea.subdef.dispatcher';
    const EXCHANGE_MONITOR = 'phrasea.monitor';

    const QUEUE_ERRORS = 'phrasea.subdefs.errors';
    const QUEUE_LOGS = 'phrasea.subdefs.logs';
    const QUEUE_IMAGE_PROCESSING = 'phrasea.subdefs.image';
    const QUEUE_VIDEO_PROCESSING = 'phrasea.subdefs.video';

    const ROUTINGKEY_ERROR = 'phrasea.error';
    const ROUTINGKEY_LOG = 'phrasea.log';
    const ROUTINGKEY_IMAGE_PROCESSING = 'phrasea.subdef.image';
    const ROUTINGKEY_VIDEO_PROCESSING = 'phrasea.subdef.video';
}
