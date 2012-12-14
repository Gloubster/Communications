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

class Factory
{
    public static function fromJson($json)
    {
        $data = json_decode($json, true);

        $factory = implode('\\', array_slice(explode('\\', $data['type']), 0, -1)) . '\\Factory';

        return $factory::fromJson($json);
    }
}
