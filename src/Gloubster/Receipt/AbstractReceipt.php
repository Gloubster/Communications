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

abstract class AbstractReceipt implements ReceiptInterface
{
    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data)
    {
        return Factory::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $data = array(
            'name' => $this->getName(),
            'type' => get_class($this),
        );

        foreach ($this as $key => $value) {
            if (in_array($key, array('client', 'type'))) {
                continue;
            }
            $data[$key] = $value;
        }

        return $data;
    }
}
