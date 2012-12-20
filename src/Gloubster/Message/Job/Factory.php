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

use Gloubster\Exception\RuntimeException;
use Gloubster\Delivery\Factory as DeliveryFactory;
use Gloubster\Receipt\Factory as ReceiptFactory;

class Factory
{

    public static function fromJson($json)
    {
        $data = json_decode($json, true);

        if (null === $data) {
            throw new RuntimeException('Invalid Json data');
        }

        if (!isset($data['type'])) {
            throw new RuntimeException('Invalid Json : Missing `type` property in object');
        }

        $classname = sprintf($data['type']);

        if (!class_exists($classname)) {
            throw new RuntimeException(sprintf('Invalid Job class : class %s does not exists', $classname));
        }

        $obj = new $classname();

        if (!$obj instanceof JobInterface) {
            throw new RuntimeException('Invalid type : JobInterface expected');
        }

        foreach ($data as $key => $serializedValue) {
            if (in_array($key, array('name', 'type'))) {
                continue;
            }
            if ($key === 'delivery' && null !== $serializedValue) {
                $obj->setDelivery(DeliveryFactory::fromArray($serializedValue));
            } elseif ($key === 'receipts' && null !== $serializedValue) {
                $receipts = array();
                foreach ($serializedValue as $receipt) {
                    $receipts[] = ReceiptFactory::fromArray($receipt);
                }
                $obj->setReceipts($receipts);
            } else {
                $obj->{'set' . ucfirst($key)}($serializedValue);
            }
        }

        return $obj;
    }
}
