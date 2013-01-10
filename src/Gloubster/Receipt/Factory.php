<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Receipt;

use Gloubster\Exception\RuntimeException;

class Factory
{
    public static function fromArray(array $data)
    {
        if (!isset($data['type'])) {
            throw new RuntimeException('Invalid receipt data : missing key `type`');
        }

        $classname = $data['type'];

        if (!class_exists($classname)) {
            throw new RuntimeException(sprintf('Invalid receipt data : class %s does not exists', $classname));
        }

        $obj = new $classname;

        if (!$obj instanceof ReceiptInterface) {
            throw new RuntimeException('Invalid receipt data, ReceiptInterface expected');
        }

        foreach ($data as $key => $serializedValue) {
            if (in_array($key, array('name', 'type'))) {
                continue;
            }
            $obj->{'set' . ucfirst($key)}($serializedValue);
        }

        return $obj;
    }
}
