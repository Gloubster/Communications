<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Delivery;

use Gloubster\Exception\InvalidArgumentException;

/**
 * Delivery factory
 */
class Factory
{

    public static function build($name, $configuration)
    {
        $classname = __NAMESPACE__ . '\\' . $name;

        if (class_exists($classname)) {
            return $classname::build($configuration);
        }

        throw new InvalidArgumentException(sprintf('Invalid delivery name `%s`', $name));
    }
}