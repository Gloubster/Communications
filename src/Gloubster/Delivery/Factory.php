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

use Gloubster\Exception\RuntimeException;

class Factory
{
    public static function fromArray(array $data)
    {
        if (!isset($data['name'])) {
            throw new RuntimeException('Invalid delivery data : missing key `name`');
        }

        $name = implode('', array_map(function ($chunk) {
            return ucfirst($chunk);
        }, explode('-', $data['name'])));

        $classname = sprintf('%s\\%s', __NAMESPACE__, $name);

        if (!class_exists($classname)) {
            throw new RuntimeException(sprintf('Invalid delivery data : class %s does not exists', $classname));
        }

        $obj = new $classname;

        if (!$obj instanceof DeliveryInterface) {
            throw new RuntimeException('Invalid delivery data, DeliveryInterface expected');
        }

        foreach ($data as $key => $serializedValue) {
            if ($key === 'name') {
                continue;
            }
            $obj->{'set' . ucfirst($key)}($serializedValue);
        }

        return $obj;
    }
}
