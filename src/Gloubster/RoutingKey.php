<?php

namespace Gloubster;

final class RoutingKey
{
    const WORKER = 'phrasea.worker';
    const ERROR = 'phrasea.error';
    const LOG = 'phrasea.log';
    const IMAGE_PROCESSING = 'phrasea.subdef.image';
    const VIDEO_PROCESSING = 'phrasea.subdef.video';
}
