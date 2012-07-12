<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\ProxySource;

use Gloubster\Configuration;
use Gloubster\Exception\InvalidArgumentException;

/**
 * Proxy factory
 */
class Factory
{

    public static function build(Configuration $configuration)
    {
        $classname = __NAMESPACE__ . '\\' . $configuration['proxy']['name'];

        if (class_exists($classname)) {
            return $classname::build($configuration['proxy']['configuration']);
        }

        throw new InvalidArgumentException(sprintf('Invalid proxy name `%s`', $configuration['delivery']['name']));
    }
}
