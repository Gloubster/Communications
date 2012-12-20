<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Message;

use Gloubster\Exception\RuntimeException;

class Factory
{

    public static function fromJson($json)
    {
        $data = json_decode($json, true);

        if (null === $data) {
            throw new RuntimeException('Invalid Json data');
        }

        if (!isset($data['type'])) {
            throw new RuntimeException('Message has no attribute type');
        }

        $factory = implode('\\', array_slice(explode('\\', $data['type']), 0, -1)) . '\\Factory';

        if (!class_exists($factory) || !method_exists($factory, 'fromJson')) {
            throw new RuntimeException(sprintf('%s does not seem to be a valid factory', $factory));
        }

        return $factory::fromJson($json);
    }
}
